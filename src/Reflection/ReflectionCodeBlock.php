<?php

namespace PhpAnalyzer\Reflection;

/**
 * Reflection of a code block.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class ReflectionCodeBlock
{
    /**
     * @var ReflectionVariable[]
     */
    private $variables = [];

    public function addVariable(ReflectionVariable $variable)
    {
        $this->variables[$variable->getName()] = $variable;
    }

    /**
     * @return ReflectionVariable[]
     */
    public function getVariables()
    {
        return $this->variables;
    }

    /**
     * @param string $name
     * @return ReflectionVariable|null
     */
    public function getVariable($name)
    {
        return isset($this->variables[$name]) ? $this->variables[$name] : null;
    }
}
