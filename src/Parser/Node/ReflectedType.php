<?php

namespace PhpAnalyzer\Parser\Node;

/**
 * Interface, class or trait.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
interface ReflectedType extends ReflectedNode
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
     * @return ReflectedMethod|null
     */
    public function getMethod($name);

    /**
     * @return ReflectedProperty[]
     */
    public function getProperties();

    /**
     * @param string $name
     * @return ReflectedProperty|null
     */
    public function getProperty($name);
}
