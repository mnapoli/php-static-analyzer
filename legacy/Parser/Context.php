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
        $this->currentScope = $rootScope;
    }

    public function enterClass(ReflectedType $class)
    {
        $this->currentClass = $class;
    }

    public function leaveClass()
    {
        $this->currentClass = null;
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
        $this->currentScope = $this->rootScope;
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
     * @return Scope
     */
    public function getRootScope()
    {
        return $this->rootScope;
    }
}
