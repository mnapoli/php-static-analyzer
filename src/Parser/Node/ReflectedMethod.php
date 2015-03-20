<?php

namespace PhpAnalyzer\Parser\Node;

use PhpAnalyzer\Scope\Scope;
use PhpAnalyzer\Type\Type;
use PhpAnalyzer\Type\UnknownType;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Param;
use PhpParser\Node\Stmt\ClassMethod;

/**
 * Method
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class ReflectedMethod extends ClassMethod
{
    /**
     * @var ReflectedType
     */
    protected $class;

    /**
     * @var Scope
     */
    private $scope;

    /**
     * @var MethodCall
     */
    private $calls;

    public function __construct(ClassMethod $node, ReflectedType $class, Scope $scope)
    {
        $this->class = $class;
        $this->scope = $scope;

        parent::__construct($node->name, $node->subNodes, $node->getAttributes());
    }

    /**
     * @return ReflectedType
     */
    public function getDeclaringClass()
    {
        return $this->class;
    }

    /**
     * @return Type
     */
    public function getReturnType()
    {
        // TODO
        return new UnknownType;
    }

    /**
     * @return Scope
     */
    public function getScope()
    {
        return $this->scope;
    }

    /**
     * @return Param[]
     */
    public function getParameters()
    {
        return $this->params;
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
