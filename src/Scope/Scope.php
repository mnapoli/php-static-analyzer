<?php

namespace PhpAnalyzer\Scope;

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
     * @var Variable[]
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

    public function addVariable(Variable $variable)
    {
        $this->variables[$variable->getName()] = $variable;
    }

    /**
     * @param bool $parent Look up in parent scope too.
     * @return Variable[]
     */
    public function getVariables($parent = true)
    {
        $variables = $this->variables;

        if ($parent && $this->parentScope) {
            $variables = array_merge($this->parentScope->getVariables(), $variables);
        }

        return $variables;
    }

    /**
     * @param string $name
     * @param bool   $parent Look up in parent scope too.
     * @return Variable|null
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
