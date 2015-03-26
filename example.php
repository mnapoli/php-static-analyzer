<?php

use PhpAnalyzer\Analyzer;

require_once 'vendor/autoload.php';

$analyzer = new Analyzer;

$project = $analyzer->analyze(__DIR__ . '/src');

printf("%d classes found\n", count($project->getClasses()));

foreach ($project->getClasses() as $class) {
    printf("%s\n", $class->getFQN());

    foreach ($class->getProperties() as $property) {
        printf("\t$%s\n", $property->getName());
    }

    foreach ($class->getMethods() as $method) {
        printf("\t%s\n", $method->getName());
        foreach ($method->getScope()->getVariables() as $variable) {
            $class = str_replace('PhpAnalyzer\Scope\\', '', get_class($variable));
            printf("\t\t$%s (%s)\n", $variable->getName(), $class);
        }
    }

    echo "\n";
}
