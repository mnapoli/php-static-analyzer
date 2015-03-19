<?php

use PhpAnalyzer\Analyzer;
use Symfony\Component\Console\Output\OutputInterface;

require_once 'vendor/autoload.php';

$app = new Silly\Application;

$analyzer = new Analyzer;
$scope = $analyzer->analyze(__DIR__ . '/src');

$app->command('method class method', function ($class, $method, OutputInterface $output) use ($scope) {
    $class = str_replace('.', '\\', $class);
    $class = $scope->getClass($class);
    $method = $class->getMethod($method);

    $output->writeln(sprintf('function %s()', $method->getName()));
    foreach ($method->getScope()->getVariables() as $variable) {
        $class = str_replace('PhpAnalyzer\Scope\\', '', get_class($variable));
        $output->writeln(sprintf("\t$%s: %s (%s)", $variable->getName(), $variable->getType()->toString(), $class));
    }
});

$app->run();
