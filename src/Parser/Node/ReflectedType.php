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
     * @param int|null $visibility Allows to filter the visibility of the properties to return.
     * @return ReflectedMethod[]
     */
    public function getMethods($visibility = null);

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
     * @param int|null $visibility Allows to filter the visibility of the properties to return.
     * @return ReflectedProperty[]
     */
    public function getProperties($visibility = null);

    /**
     * @param string $name
     * @return ReflectedProperty|null
     */
    public function getProperty($name);
}
