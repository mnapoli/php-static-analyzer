<?php

namespace PhpAnalyzer\Test;

use PhpAnalyzer\Analyzer;
use PhpAnalyzer\Reflection\ReflectionMethod;
use PhpAnalyzer\Test\Fixture\Blog;

class AnalyzerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function should_list_classes()
    {
        $this->markTestIncomplete();
        $classes = $this->analyze()->getClasses();

        $this->assertCount(2, $classes);
        $this->assertEquals('PhpAnalyzer\Test\Fixture\Blog', $classes[0]->getName());
        $this->assertEquals('PhpAnalyzer\Test\Fixture\Article', $classes[1]->getName());
    }

    /**
     * @test
     */
    public function should_list_methods_in_classes()
    {
        $this->markTestIncomplete();
        $blogClass = $this->analyze()->getClass(Blog::class);

        $methods = $blogClass->getMethods();

        $this->assertCount(3, $methods);
        $methodNames = array_map(function (ReflectionMethod $method) {
            return $method->getName();
        }, $methods);
        $this->assertEquals([
            'addArticle',
            'publish',
            'save',
        ], $methodNames);
    }

    /**
     * @test
     */
    public function methods_should_have_callers_array()
    {
        $this->markTestIncomplete();
        $blogClass = $this->analyze()->getClass(Blog::class);

        $method = $blogClass->getMethod('save');
        $calls = $method->getCalls();

        $this->assertCount(1, $calls);
    }

    private function analyze()
    {
        $result = (new Analyzer)->analyze(__DIR__ . '/Fixture');
        return $result;
    }
}
