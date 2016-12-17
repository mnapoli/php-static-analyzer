<?php
declare(strict_types = 1);

namespace PhpAnalyzer\Test;

use PhpAnalyzer\Node\Declaration\Class_;
use PhpAnalyzer\Scope\Exception\VariableDoesNotExist;
use PhpAnalyzer\Scope\Scope;
use PhpAnalyzer\Scope\Variable;

/**
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class FakeScope implements Scope
{
    /**
     * @var Class_[]
     */
    private $classes = [];

    /**
     * @var Variable[]
     */
    private $variables = [];

    public function hasClass(string $name) : bool
    {
        return isset($this->classes[$name]);
    }

    public function getClass(string $name) : Class_
    {
        if (!$this->hasClass($name)) {
            throw new \Exception('Class not found');
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

    public function hasVariable(string $name) : bool
    {
        return isset($this->variables[$name]);
    }

    public function getVariable(string $name) : Variable
    {
        if (! isset($this->variables[$name])) {
            throw new VariableDoesNotExist();
        }
        return $this->variables[$name];
    }

    public function getVariables() : array
    {
        return $this->variables;
    }
}
