<?php

namespace PhpAnalyzer;

use PhpAnalyzer\Parser\Node\ReflectedClass;

/**
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class Scope
{
    /**
     * @var Scope
     */
    private $parentScope;

    /**
     * @var ReflectedClass[]
     */
    private $classes = [];

    /**
     * @var ReflectedVariable[]
     */
    private $variables = [];

    public function __construct(Scope $parentScope = null)
    {
        $this->parentScope = $parentScope;
    }

    public function enterSubScope()
    {
        return new self($this);
    }

    public function addClass(ReflectedClass $class)
    {
        $this->classes[$class->getFQN()] = $class;
    }

    /**
     * @return ReflectedClass[]
     */
    public function getClasses()
    {
        return array_values($this->classes);
    }

    /**
     * @param string $name
     * @return ReflectedClass
     */
    public function getClass($name)
    {
        return $this->classes[$name];
    }

    public function addVariable(ReflectedVariable $variable)
    {
        $this->variables[$variable->getName()] = $variable;
    }

    /**
     * @return ReflectedVariable[]
     */
    public function getVariables()
    {
        return $this->variables;
    }

    /**
     * @param string $name
     * @param bool   $parent Look up in parent scope too.
     * @return ReflectedVariable|null
     */
    public function getVariable($name, $parent = true)
    {
        if (isset($this->variables[$name])) {
            return $this->variables[$name];
        }

        if ($parent && $this->parentScope) {
            return $this->parentScope->getVariable($name);
        }

        return null;
    }
}
