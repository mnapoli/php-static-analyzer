<?php

namespace PhpAnalyzer\Scope;

use PhpAnalyzer\Parser\Node\ReflectedClass;
use PhpAnalyzer\Type\ClassType;

/**
 * `$this` variable representing the current class.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class This extends Variable
{
    /**
     * @var ReflectedClass
     */
    private $class;

    public function __construct(ReflectedClass $class)
    {
        $this->class = $class;
    }

    public function getName()
    {
        return 'this';
    }

    public function getType()
    {
        return new ClassType($this->class);
    }
}
