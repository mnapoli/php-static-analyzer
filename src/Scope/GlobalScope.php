<?php

namespace PhpAnalyzer\Scope;

use PhpAnalyzer\Node\Declaration\Class_;
use PhpAnalyzer\Project;

/**
 * Represents the global scope.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class GlobalScope implements Scope
{
    /**
     * @var Project
     */
    private $project;

    /**
     * @var Variable[]
     */
    private $variables = [];

    public function __construct(Project $project)
    {
        $this->project = $project;
    }

    public function hasClass(string $name) : bool
    {
        return $this->project->hasClass($name);
    }

    public function getClass(string $name) : Class_
    {
        return $this->project->getClass($name);
    }

    public function addVariable(Variable $variable)
    {
        if ($this->hasVariable($variable->getName())) {
            throw new \LogicException(sprintf('A "%s" variable already exist in that scope', $variable->getName()));
        }

        $this->variables[$variable->getName()] = $variable;
    }

    public function hasVariable(string $name) : bool
    {
        return isset($this->variables[$name]);
    }

    public function getVariable(string $name) : Variable
    {
        if (isset($this->variables[$name])) {
            return $this->variables[$name];
        }

        return null;
    }

    public function getVariables() : array
    {
        return $this->variables;
    }
}
