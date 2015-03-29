<?php

namespace PhpAnalyzer\Test\Integration;

use PhpAnalyzer\Analyzer;

abstract class BaseAnalyzerTest extends \PHPUnit_Framework_TestCase
{
    protected function analyze($dir)
    {
        return (new Analyzer)->analyze($dir);
    }

    protected function analyzeClass($dir, $class)
    {
        return $this->analyze($dir)->getClass($class);
    }
}
