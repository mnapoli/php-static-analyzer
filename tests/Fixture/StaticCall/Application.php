<?php

namespace PhpAnalyzer\Test\Fixture\StaticCall;

class Application
{
    public function run()
    {
        Logger::warning('Foo');
    }
}
