<?php

namespace PhpAnalyzer\Log;

use Psr\Log\LoggerInterface;

/**
 * A static class, I know, it should go away some day.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class Logger
{
    /**
     * @var LoggerInterface
     */
    private static $logger;

    public static function setLogger(LoggerInterface $logger)
    {
        self::$logger = $logger;
    }

    public static function error($message, array $context = [])
    {
        if (! self::$logger) {
            return;
        }

        self::$logger->error($message, $context);
    }

    public static function warning($message, array $context = [])
    {
        if (! self::$logger) {
            return;
        }

        self::$logger->warning($message, $context);
    }

    public static function notice($message, array $context = [])
    {
        if (! self::$logger) {
            return;
        }

        self::$logger->notice($message, $context);
    }

    public static function info($message, array $context = [])
    {
        if (! self::$logger) {
            return;
        }

        self::$logger->info($message, $context);
    }

    public static function debug($message, array $context = [])
    {
        if (! self::$logger) {
            return;
        }

        self::$logger->debug($message, $context);
    }
}
