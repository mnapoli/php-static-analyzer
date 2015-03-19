<?php

namespace PhpAnalyzer\Scope;

use PhpAnalyzer\Parser\Node\ReflectedType;

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
     * @var ReflectedType[]
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

    public function addClass(ReflectedType $class)
    {
        $this->classes[$class->getFQN()] = $class;
    }

    /**
     * @return ReflectedType[]
     */
    public function getClasses()
    {
        return array_values($this->classes);
    }

    public function hasClass($name)
    {
        return isset($this->classes[$name]);
    }

    /**
     * @param string $name
     * @return ReflectedType
     */
    public function getClass($name)
    {
        if (! $this->hasClass($name)) {
            throw new \LogicException(sprintf('Class %s not found', $name));
        }

        return $this->classes[$name];
    }

    public function addVariable(Variable $variable)
    {
        if ($this->hasVariable($variable->getName())) {
            throw new \LogicException(sprintf('A "%s" variable already exist in that scope', $variable->getName()));
        }

        $this->variables[$variable->getName()] = $variable;
    }

    /**
     * @param string $name
     * @return bool
     */
    public function hasVariable($name)
    {
        if ($this->parentScope && $this->parentScope->hasVariable($name)) {
            return true;
        }

        return isset($this->variables[$name]);
    }

    /**
     * @return Variable[]
     */
    public function getVariables()
    {
        $variables = $this->variables;

        if ($this->parentScope) {
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
