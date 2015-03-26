<?php

namespace PhpAnalyzer\Parser\Node;

/**
 * Call to a callable.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
interface ReflectedCallableCall extends ReflectedNode
{
    /**
     * @return ReflectedCallable
     */
    public function getTargetCallable();
}
