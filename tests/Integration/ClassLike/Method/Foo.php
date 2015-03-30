<?php

namespace PhpAnalyzer\Test\Integration\ClassLike\Method;

class Foo
{
    public function withParameters($param1, $param2)
    {
    }

    /**
     * @return \PhpAnalyzer\Test\Integration\ClassLike\Method\Foo
     */
    public function returnNamespacedClass()
    {
    }

    /**
     * @return string
     */
    public function returnString()
    {
    }
}
