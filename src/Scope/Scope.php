<?php

namespace PhpAnalyzer\Scope;

use PhpAnalyzer\Node\Declaration\Class_;
use PhpAnalyzer\Scope\Exception\VariableDoesNotExist;

/**
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
interface Scope
{
    public function hasClass(string $name) : bool;

    public function getClass(string $name) : Class_;

    public function addVariable(Variable $variable);

    public function hasVariable(string $name) : bool;

    /**
     * @throws VariableDoesNotExist
     */
    public function getVariable(string $name) : Variable;

    /**
     * @return Variable[]
     */
    public function getVariables() : array;
}
