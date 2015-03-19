<?php

namespace PhpAnalyzer\Closure;

/**
 * Cache a closure's result.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class CachedClosure
{
    /**
     * @var callable
     */
    private $callable;

    /**
     * @var mixed
     */
    private $result;

    public function __construct(callable $callable)
    {
        $this->callable = $callable;
    }

    public function __invoke()
    {
        if ($this->result === null) {
            $this->result = call_user_func($this->callable);
        }

        return $this->result;
    }
}
