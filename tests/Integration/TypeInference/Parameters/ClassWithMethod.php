<?php

namespace PhpAnalyzer\Test\Integration\TypeInference\Parameters;

class ClassWithMethod
{
    public function foo(SimpleClass $callee)
    {
    }
}
