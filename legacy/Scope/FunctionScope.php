<?php

namespace PhpAnalyzer\Scope;

use PhpAnalyzer\Parser\Node\ReflectedType;

/**
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class FunctionScope implements Scope
{
    /**
     * @var Scope
     */
    private $parentScope;

    /**
     * @var Variable[]
     */
    private $variables = [];

    public function __construct(Scope $parentScope)
    {
        $this->parentScope = $parentScope;
    }

    /**
     * {@inheritdoc}
     */
    public function addClass(ReflectedType $class)
    {
        throw new \LogicException('No class can be defined in a function');
    }

    /**
     * {@inheritdoc}
     */
    public function hasClass($name)
    {
        return $this->parentScope->hasClass($name);
    }

    /**
     * {@inheritdoc}
     */
    public function getClass($name)
    {
        return $this->parentScope->getClass($name);
    }

    /**
     * {@inheritdoc}
     */
    public function getClasses()
    {
        return $this->parentScope->getClasses();
    }

    /**
     * {@inheritdoc}
     */
    public function addVariable(Variable $variable)
    {
        if ($this->hasVariable($variable->getName())) {
            throw new \LogicException(sprintf('A "%s" variable already exist in that scope', $variable->getName()));
        }

        $this->variables[$variable->getName()] = $variable;
    }

    /**
     * {@inheritdoc}
     */
    public function hasVariable($name)
    {
        return isset($this->variables[$name]);
    }

    /**
     * {@inheritdoc}
     */
    public function getVariable($name)
    {
        if (isset($this->variables[$name])) {
            return $this->variables[$name];
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function getVariables()
    {
        return $this->variables;
    }
}
