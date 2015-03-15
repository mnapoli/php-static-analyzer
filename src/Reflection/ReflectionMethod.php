<?php

namespace PhpAnalyzer\Reflection;

use PhpParser\Node\Stmt\ClassMethod;

/**
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class ReflectionMethod extends AbstractReflection
{
    /**
     * @var ClassMethod
     */
    protected $node;

    /**
     * @var ReflectionClass
     */
    private $class;

    public function __construct(ClassMethod $node, ReflectionClass $class)
    {
        $this->class = $class;

        parent::__construct($node);
    }

    /**
     * @return ReflectionClass
     */
    public function getDeclaringClass()
    {
        return $this->class;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->node->name;
    }

    /**
     * @return bool
     */
    public function isFinal()
    {
        return $this->node->isFinal();
    }

    /**
     * @return bool
     */
    public function isAbstract()
    {
        return $this->node->isAbstract();
    }

    /**
     * @return bool
     */
    public function isConstructor()
    {
        return $this->getName() === '__construct';
    }

    /**
     * @return bool
     */
    public function isDestructor()
    {
        return $this->getName() === '__destruct';
    }

    /**
     * @return bool
     */
    public function isPrivate()
    {
        return $this->node->isPrivate();
    }

    /**
     * @return bool
     */
    public function isProtected()
    {
        return $this->node->isProtected();
    }

    /**
     * @return bool
     */
    public function isPublic()
    {
        return $this->node->isPublic();
    }

    /**
     * @return bool
     */
    public function isStatic()
    {
        return $this->node->isStatic();
    }

    // Methods from the PHP reflection
//    public function getModifiers ();
//    public function getPrototype ();
}
