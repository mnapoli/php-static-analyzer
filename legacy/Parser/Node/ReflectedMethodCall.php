<?php

namespace PhpAnalyzer\Parser\Node;

use PhpAnalyzer\Log\Logger;
use PhpAnalyzer\Scope\Scope;
use PhpAnalyzer\Type\ClassType;
use PhpAnalyzer\Type\UnknownType;
use PhpParser\Node\Expr\MethodCall;

/**
 * Method call.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class ReflectedMethodCall extends MethodCall implements TypedNode, ReflectedCallableCall
{
    use BaseNode;

    /**
     * @var Scope
     */
    private $currentScope;

    /**
     * @var ReflectedMethod|null
     */
    private $method;

    public function __construct(MethodCall $node, Scope $currentScope)
    {
        $this->currentScope = $currentScope;

        parent::__construct($node->var, $node->name, $node->args, $node->attributes);
    }

    /**
     * Returns the method called, or null if unknown.
     *
     * @return ReflectedMethod|null
     */
    public function getTargetCallable()
    {
        if ($this->method) {
            return $this->method;
        }

        if (! is_string($this->name)) {
            // TODO no support for dynamic method name
            return null;
        }

        if (! $this->var instanceof TypedNode) {
            // TODO only support method call on nodes that are typed
            return null;
        }

        $variableType = $this->var->getNodeType();

        if (! $variableType instanceof ClassType) {
            Logger::warning('Method call on non-object');
            return null;
        }

        try {
            $class = $this->currentScope->getClass($variableType->toString());
        } catch (\LogicException $e) {
            Logger::warning('Method call on unknown class {class}', ['class' => $variableType->toString()]);
            return null;
        }

        try {
            // TODO check the method is not static
            $this->method = $class->getMethod($this->name);
        } catch (\LogicException $e) {
            Logger::warning('Method call on unknown method {class}::{method}()', [
                'class'  => $class->getFQN(),
                'method' => $this->name,
            ]);
            return null;
        }

        return $this->method;
    }

    public function getNodeType()
    {
        $method = $this->getTargetCallable();

        if (! $method) {
            return new UnknownType;
        }

        return $method->getReturnType();
    }
}
