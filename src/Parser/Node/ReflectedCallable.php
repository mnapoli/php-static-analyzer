<?php

namespace PhpAnalyzer\Parser\Node;

/**
 * Callable (function, method, ...).
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
interface ReflectedCallable extends ReflectedNode
{
    public function addCall(ReflectedCallableCall $call);

    /**
     * @return ReflectedCallableCall[]
     */
    public function getCalls();
}
