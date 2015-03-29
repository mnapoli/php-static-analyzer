<?php

namespace PhpAnalyzer\Test\Integration\CallHierarchy\MethodCall;

class Caller
{
    public function foo(Callee $callee)
    {
        $callee->bar();
        StaticCallee::staticMethod();
    }
}
