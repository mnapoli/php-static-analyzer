<?php

namespace PhpAnalyzer\Parser\Node;

use PhpAnalyzer\Scope\Scope;
use PhpAnalyzer\Type\ClassType;
use PhpAnalyzer\Type\Type;
use PhpAnalyzer\Type\UnknownType;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\Variable;

/**
 * Method call.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class ReflectedMethodCall extends MethodCall
{
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
    public function getMethod()
    {
        if ($this->method) {
            return $this->method;
        }

        if (! is_string($this->name)) {
            // TODO no support for dynamic method name
            return null;
        }

        $variable = $this->var;

        if (! $variable instanceof Variable) {
            // TODO only support method call on variable
            return null;
        }

        if (! is_string($variable->name)) {
            // TODO no support for dynamic variable name
            return null;
        }

        $variable = $this->currentScope->getVariable($variable->name);
        $variableType = $variable->getType();

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

        $this->method = $class->getMethod($this->name);

        return $this->method;
    }

    /**
     * Returns the method called, or null if unknown.
     *
     * @return Type
     */
    public function getReturnType()
    {
        $method = $this->getMethod();

        if (! $method) {
            return new UnknownType;
        }

        return $method->getReturnType();
    }
}
