<?php

use PhpAnalyzer\Analyzer;
use PhpAnalyzer\Parser\Node\ReflectedMethod;
use Symfony\Component\Console\Output\OutputInterface;

require_once 'vendor/autoload.php';

$app = new Silly\Application;

$directories = [
    __DIR__ . '/src',
//    __DIR__ . '/vendor/nikic',
];

$app->command('info class [method]', function ($class, $method, OutputInterface $output) use ($directories) {
    $analyzer = new Analyzer;
    $scope = $analyzer->analyze($directories);

    $class = str_replace('.', '\\', $class);
    $class = $scope->getClass($class);
    if ($method) {
        $methods = [$class->getMethod($method)];
    } else {
        $methods = $class->getMethods();
    }

    foreach ($methods as $method) {
        /** @var ReflectedMethod $method */
        $output->writeln(sprintf('function %s()', $method->getName()));
        foreach ($method->getScope()->getVariables() as $variable) {
            $class = str_replace('PhpAnalyzer\Scope\\', '', get_class($variable));
            $output->writeln(sprintf("\t$%s: %s (%s)", $variable->getName(), $variable->getType()->toString(), $class));
        }
    }
});

$app->run();
