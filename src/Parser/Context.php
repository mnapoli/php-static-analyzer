<?php

namespace PhpAnalyzer\Parser;

use PhpAnalyzer\Parser\Node\ReflectedMethod;
use PhpAnalyzer\Parser\Node\ReflectedType;
use PhpAnalyzer\Scope\Scope;

/**
 * Context while traversing an AST.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class Context
{
    /**
     * @var Scope
     */
    private $rootScope;

    /**
     * @var Scope
     */
    private $currentScope;

    /**
     * @var ReflectedType|null
     */
    private $currentClass;

    /**
     * @var ReflectedMethod|null
     */
    private $currentMethod;

    public function __construct(Scope $rootScope)
    {
        $this->rootScope = $rootScope;
    }

    public function enterClass(ReflectedType $class)
    {
        $this->currentClass = $class;
        $this->currentScope = $class->getScope();
    }

    public function leaveClass()
    {
        $this->currentClass = null;
        $this->currentScope = $this->rootScope;
    }

    /**
     * @return ReflectedType|null
     */
    public function getCurrentClass()
    {
        return $this->currentClass;
    }

    public function enterMethod(ReflectedMethod $method)
    {
        $this->currentMethod = $method;
        $this->currentScope = $method->getScope();
    }

    public function leaveMethod()
    {
        $this->currentMethod = null;
        $this->currentScope = $this->currentClass->getScope();
    }

    /**
     * @return ReflectedMethod|null
     */
    public function getCurrentMethod()
    {
        return $this->currentMethod;
    }

    /**
     * @return Scope
     */
    public function getCurrentScope()
    {
        return $this->currentScope;
    }

    /**
     * @return \PhpAnalyzer\Scope\Scope
     */
    public function getRootScope()
    {
        return $this->rootScope;
    }
}
