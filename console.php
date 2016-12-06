<?php

use PhpAnalyzer\File;
use PhpAnalyzer\Log\Logger;
use Symfony\Component\Console\Logger\ConsoleLogger;
use Symfony\Component\Console\Output\OutputInterface;

require_once 'vendor/autoload.php';

gc_disable();

$app = new Silly\Application;

$app->command(
    'info [class] [method] [--directories=]* [--save=]',
    function ($class, $method, array $directories, $save, OutputInterface $output) {
        Logger::setLogger(new ConsoleLogger($output));

        $file = new File(__DIR__ . '/src/File.php');

        echo json_encode($file->toArray(), JSON_PRETTY_PRINT);
    }
);

$app->run();
