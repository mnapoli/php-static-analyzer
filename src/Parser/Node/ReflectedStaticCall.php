<?php

namespace PhpAnalyzer\Parser\Node;

use PhpAnalyzer\Scope\Scope;
use PhpAnalyzer\Type\UnknownType;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Name;

/**
 * Static method call.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class ReflectedStaticCall extends StaticCall implements TypedNode
{
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
            // TODO log error: unknown class
            return null;
        }
    }

    /**
     * Returns the method called, or null if unknown.
     *
     * @return ReflectedMethod|null
     */
    public function getMethod()
    {
        if (! is_string($this->name)) {
            // TODO no support for dynamic method name
            return null;
        }

        $class = $this->getTargetClass();

        if (! $class) {
            return null;
        }

        return $class->getMethod($this->name);
    }

    public function getNodeType()
    {
        $method = $this->getMethod();

        if (! $method) {
            return new UnknownType;
        }

        return $method->getReturnType();
    }
}
