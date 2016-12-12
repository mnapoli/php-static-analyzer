<?php

namespace PhpAnalyzer\Scope;

use PhpAnalyzer\Parser\Node\ReflectedType;
use PhpAnalyzer\Type\ObjectType;
use PhpAnalyzer\Type\Type;

/**
 * `$this` variable representing the current class.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class This extends Variable
{
    /**
     * @var ReflectedType
     */
    private $class;

    public function __construct(ReflectedType $class)
    {
        $this->class = $class;
    }

    public function getName()
    {
        return 'this';
    }

    public function getType()
    {
        return new ObjectType($this->class);
    }

    public function addType(Type $type)
    {
        // ignore, we can't change the type of '$this'
    }
}
