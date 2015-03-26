<?php

namespace PhpAnalyzer\Test\Fixture\StaticCall;

class Logger
{
    /**
     * @deprecated
     *
     * @param string $message
     */
    public static function warning($message, \stdClass $infos)
    {
        echo $message;
    }
}
