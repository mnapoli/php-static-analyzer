<?php

namespace PhpAnalyzer\Parser\Node;

use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\Property;
use PhpParser\Node\Stmt\PropertyProperty;

/**
 * Property
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class ReflectedProperty extends Stmt
{
    /**
     * @var Property
     */
    private $propertyBlock;

    /**
     * @var PropertyProperty
     */
    private $property;

    /**
     * @var ReflectedType
     */
    private $class;

    public function __construct(Property $propertyBlock, PropertyProperty $property, ReflectedType $class)
    {
        $this->propertyBlock = $propertyBlock;
        $this->property = $property;
        $this->class = $class;

        parent::__construct([], []);
    }

    /**
     * @return ReflectedType
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
        return $this->property->name;
    }

    public function isPublic() {
        return $this->propertyBlock->isPublic();
    }

    public function isProtected() {
        return $this->propertyBlock->isProtected();
    }

    public function isPrivate() {
        return $this->propertyBlock->isPrivate();
    }

    public function isStatic() {
        return $this->propertyBlock->isStatic();
    }
}
