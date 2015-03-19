<?php

namespace PhpAnalyzer\Parser\Node;

use PhpAnalyzer\Scope\Scope;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Stmt\ClassMethod;

/**
 * Method
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class ReflectedMethod extends ClassMethod
{
    /**
     * @var ReflectedClass
     */
    protected $class;

    /**
     * @var \PhpAnalyzer\Scope\Scope
     */
    private $scope;

    /**
     * @var MethodCall
     */
    private $calls;

    public function __construct(ClassMethod $node, ReflectedClass $class, Scope $scope)
    {
        $this->class = $class;
        $this->scope = $scope;

        parent::__construct($node->name, $node->subNodes, $node->getAttributes());
    }

    /**
     * @return ReflectedClass
     */
    public function getDeclaringClass()
    {
        return $this->class;
    }

    /**
     * @return Scope
     */
    public function getScope()
    {
        return $this->scope;
    }

    public function addCall(MethodCall $call)
    {
        $this->calls[] = $call;
    }

    /**
     * @return MethodCall[]
     */
    public function getCalls()
    {
        return $this->calls;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return bool
     */
    public function isConstructor()
    {
        return $this->name === '__construct';
    }

    /**
     * @return bool
     */
    public function isDestructor()
    {
        return $this->name === '__destruct';
    }
}