<?php

namespace PhpAnalyzer\Type;

use PhpAnalyzer\Parser\Node\ReflectedType;

/**
 * Class
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class ClassType implements Type
{
    /**
     * @var ReflectedType
     */
    private $class;

    public function __construct(ReflectedType $class)
    {
        $this->class = $class;
    }

    public function toString()
    {
        return $this->class->getFQN();
    }
}
