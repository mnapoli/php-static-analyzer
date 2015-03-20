<?php

namespace PhpAnalyzer\Parser\Node;

/**
 * Call to a callable.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
interface ReflectedCallableCall
{
    /**
     * @return ReflectedCallable
     */
    public function getTargetCallable();
}
