<?php

namespace PhpAnalyzer\Parser;

use PhpAnalyzer\Reflection\ReflectionClass;
use PhpAnalyzer\Reflection\ReflectionMethod;

/**
 * Context while traversing an AST.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class Context
{
    /**
     * @var ReflectionClass|null
     */
    private $currentClass;

    /**
     * @var ReflectionMethod|null
     */
    private $currentMethod;

    public function enterClass(ReflectionClass $class)
    {
        $this->currentClass = $class;
    }

    public function leaveClass()
    {
        $this->currentClass = null;
    }

    /**
     * @return ReflectionClass|null
     */
    public function getCurrentClass()
    {
        return $this->currentClass;
    }

    public function enterMethod(ReflectionMethod $method)
    {
        $this->currentMethod = $method;
    }

    public function leaveMethod()
    {
        $this->currentMethod = null;
    }
}
