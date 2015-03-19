<?php

use PhpAnalyzer\Analyzer;

require_once 'vendor/autoload.php';

$analyzer = new Analyzer;

$scope = $analyzer->analyze(__DIR__ . '/src');

printf("%d classes found\n", count($scope->getClasses()));

foreach ($scope->getClasses() as $class) {
    printf("%s\n", $class->getFQN());

    foreach ($class->getProperties() as $property) {
        printf("\t$%s\n", $property->getName());
    }

    foreach ($class->getMethods() as $method) {
        printf("\t%s\n", $method->getName());
        foreach ($method->getScope()->getVariables() as $variable) {
            printf("\t\t$%s\n", $variable->getName());
        }
    }

    echo "\n";
}
