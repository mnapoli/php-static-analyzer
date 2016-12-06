<?php

namespace PhpAnalyzer\Scope;

use PhpAnalyzer\Parser\Node\ReflectedType;

/**
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
interface Scope
{
    public function addClass(ReflectedType $class);

    /**
     * @param string $name
     * @return bool
     */
    public function hasClass($name);

    /**
     * @param string $name
     * @return ReflectedType
     */
    public function getClass($name);

    /**
     * @return ReflectedType[]
     */
    public function getClasses();

    public function addVariable(Variable $variable);

    /**
     * @param string $name
     * @return bool
     */
    public function hasVariable($name);

    /**
     * @param string $name
     * @return Variable|null
     */
    public function getVariable($name);

    /**
     * @return Variable[]
     */
    public function getVariables();
}
