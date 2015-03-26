<?php

use PhpAnalyzer\Analyzer;
use PhpAnalyzer\Parser\Node\ReflectedMethod;
use Symfony\Component\Console\Output\OutputInterface;

require_once 'vendor/autoload.php';

$app = new Silly\Application;

$defaultDirectories = [
    __DIR__ . '/src',
//    __DIR__ . '/vendor/nikic',
];

$app->command('info [class] [method] [--directories=]*', function ($class, $method, array $directories, OutputInterface $output) use ($defaultDirectories) {
    if (empty($directories)) {
        $directories = $defaultDirectories;
    }

    $analyzer = new Analyzer;
    $project = $analyzer->analyze($directories);

    if ($class) {
        $class = str_replace('.', '\\', $class);
        $classes = [$project->getClass($class)];
    } else {
        $classes = $project->getClasses();
    }
    $methodName = $method;

    foreach ($classes as $class) {
        $output->writeln($class->getFQN());

        if ($methodName) {
            $methods = [$class->getMethod($methodName)];
        } else {
            $methods = $class->getMethods();
        }

        foreach ($methods as $method) {
            /** @var ReflectedMethod $method */
            $output->writeln(sprintf("\t%s()", $method->getName()));

            foreach ($method->getScope()->getVariables() as $variable) {
                $class = str_replace('PhpAnalyzer\Scope\\', '', get_class($variable));
                $output->writeln(sprintf("\t\t$%s: %s (%s)", $variable->getName(), $variable->getType()->toString(), $class));
            }

            if ($method->getAttribute('deprecated', false)) {
                $output->writeln("\t\t<info>Deprecated</info>");
            }

            foreach ($method->getCalls() as $call) {
                $output->writeln(sprintf("\t\tCalled in %s at line %d", $call->getFile()->getRelativeFileName(), $call->getLine()));
            }
        }

        $output->writeln('');
    }
});

$app->run();
