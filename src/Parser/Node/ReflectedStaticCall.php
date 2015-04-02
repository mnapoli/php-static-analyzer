<?php

namespace PhpAnalyzer\Parser\Node;

use PhpAnalyzer\Log\Logger;
use PhpAnalyzer\Scope\Scope;
use PhpAnalyzer\Type\UnknownType;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Name;

/**
 * Static method call.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class ReflectedStaticCall extends StaticCall implements TypedNode, ReflectedCallableCall
{
    use BaseNode;

    /**
     * @var Scope
     */
    private $currentScope;

    public function __construct(StaticCall $node, Scope $currentScope)
    {
        $this->currentScope = $currentScope;

        parent::__construct($node->class, $node->name, $node->args, $node->attributes);
    }

    /**
     * @return ReflectedClass|null
     */
    public function getTargetClass()
    {
        if (! $this->class instanceof Name) {
            // TODO no support for dynamic method name
            return null;
        }

        $className = $this->class->toString();

        try {
            return $this->currentScope->getClass($className);
        } catch (\LogicException $e) {
            // TODO add support for static and self
            Logger::warning('Static method call on unknown class {class}', ['class' => $className]);
            return null;
        }
    }

    /**
     * Returns the method called, or null if unknown.
     *
     * @return ReflectedMethod|null
     */
    public function getTargetCallable()
    {
        if (! is_string($this->name)) {
            // TODO no support for dynamic method name
            return null;
        }

        $class = $this->getTargetClass();

        if (! $class) {
            return null;
        }


        try {
            // TODO check the method is indeed static
            return $class->getMethod($this->name);
        } catch (\LogicException $e) {
            Logger::warning('Static call on unknown method {class}::{method}()', [
                'class'  => $class->getFQN(),
                'method' => $this->name,
            ]);
            return null;
        }
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
