<?php

namespace PhpAnalyzer\Parser;

use PhpAnalyzer\Parser\Node\ReflectedClass;
use PhpAnalyzer\Scope;
use PhpParser\Node\Stmt\ClassMethod;

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
     * @var \PhpAnalyzer\Scope
     */
    private $currentScope;

    /**
     * @var ReflectedClass|null
     */
    private $currentClass;

    /**
     * @var ClassMethod|null
     */
    private $currentMethod;

    public function __construct(Scope $rootScope)
    {
        $this->rootScope = $rootScope;
    }

    public function enterClass(ReflectedClass $class)
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
     * @return ReflectedClass|null
     */
    public function getCurrentClass()
    {
        return $this->currentClass;
    }

    public function enterMethod(ClassMethod $method)
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
     * @return ClassMethod|null
     */
    public function getCurrentMethod()
    {
        return $this->currentMethod;
    }

    /**
     * @return \PhpAnalyzer\Scope
     */
    public function getCurrentScope()
    {
        return $this->currentScope;
    }

    /**
     * @return Scope
     */
    public function getRootScope()
    {
        return $this->rootScope;
    }
}
