<?php

namespace PhpAnalyzer\Parser\Node;

use PhpAnalyzer\Scope\Scope;

/**
 * Interface, class or trait.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
interface ReflectedType
{
    /**
     * @return string
     */
    public function getFQN();

    /**
     * @return ReflectedMethod
     */
    public function getMethods();

    /**
     * @param string $name
     * @return bool
     */
    public function hasMethod($name);

    /**
     * @param string $name
     * @return null|ReflectedMethod
     */
    public function getMethod($name);

    /**
     * @return Scope
     */
    public function getScope();
}
