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
     * @return null|ReflectedMethod
     */
    public function getMethod($name);
}
