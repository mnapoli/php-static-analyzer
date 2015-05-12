<?php

namespace PhpAnalyzer\Test\Integration\ClassLike\Methods;

/**
 * The class is abstract so that it doesn't have to implement the interface's method.
 */
abstract class Implementation implements Interface_
{
    public function publicMethod2()
    {
    }
}
