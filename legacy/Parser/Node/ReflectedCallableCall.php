<?php

namespace PhpAnalyzer\Parser\Node;

/**
 * Call to a callable.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
interface ReflectedCallableCall extends Node
{
    /**
     * @return ReflectedCallable|null
     */
    public function getTargetCallable();

    /**
     * @return int
     */
    public function getLine();
}
