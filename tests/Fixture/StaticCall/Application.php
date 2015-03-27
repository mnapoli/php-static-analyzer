<?php

namespace PhpAnalyzer\Test\Fixture\StaticCall;

class Application
{
    /**
     * @return \PhpAnalyzer\Test\Fixture\StaticCall\Application
     */
    public function run()
    {
        Logger::warning('Foo');

        return $this;
    }
}
