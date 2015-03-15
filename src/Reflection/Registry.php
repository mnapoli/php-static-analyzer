<?php

namespace PhpAnalyzer\Reflection;

/**
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class Registry
{
    /**
     * @var ReflectionClass[]
     */
    private $classes = [];

    public function addClass(ReflectionClass $class)
    {
        $this->classes[$class->getName()] = $class;
    }

    /**
     * @return ReflectionClass[]
     */
    public function getClasses()
    {
        return $this->classes;
    }
}
