<?php

namespace PhpAnalyzer\Parser\Node;

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
            // TODO log error: method call on non-object
            return null;
        }

        try {
            $class = $this->currentScope->getClass($variableType->toString());
        } catch (\LogicException $e) {
            // TODO log error: unknown class
            return null;
        }

        try {
            $this->method = $class->getMethod($this->name);
        } catch (\LogicException $e) {
            // TODO log error: unknown method
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
