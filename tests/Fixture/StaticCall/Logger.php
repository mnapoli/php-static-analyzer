<?php

namespace PhpAnalyzer\Test\Fixture\StaticCall;

class Logger
{
    public static function warning($message)
    {
        echo $message;
    }
}
