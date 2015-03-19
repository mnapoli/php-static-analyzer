<?php

namespace PhpAnalyzer\Type;

use PhpAnalyzer\Parser\Node\ReflectedClass;

/**
 * Class
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class ClassType implements Type
{
    /**
     * @var ReflectedClass
     */
    private $class;

    public function __construct(ReflectedClass $class)
    {
        $this->class = $class;
    }

    public function toString()
    {
        return $this->class->getFQN();
    }
}
