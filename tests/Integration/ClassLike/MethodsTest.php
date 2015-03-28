<?php

namespace PhpAnalyzer\Test\Integration\ClassLike;

use PhpAnalyzer\Analyzer;
use PhpParser\Node\Stmt\Class_;

class MethodsTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function should_list_class_methods()
    {
        $this->markTestIncomplete('TODO');
    }

    /**
     * @test
     * @dataProvider visibilityProvider
     */
    public function should_list_class_methods_filtered_by_visibility($visibility, $methodName)
    {
        $this->markTestIncomplete('TODO');
    }

    public function visibilityProvider()
    {
        return [
            'public'    => [Class_::MODIFIER_PUBLIC, 'public'],
            'protected' => [Class_::MODIFIER_PROTECTED, 'protected'],
            'private'   => [Class_::MODIFIER_PRIVATE, 'private'],
        ];
    }

    /**
     * @test
     */
    public function methods_should_include_parent_methods()
    {
        $this->markTestIncomplete('TODO');
    }

    /**
     * @test
     */
    public function methods_should_include_trait_methods()
    {
        $this->markTestIncomplete('TODO');
    }

    /**
     * @test
     */
    public function methods_should_override_parent_methods()
    {
        $this->markTestIncomplete('TODO');
    }

    /**
     * @test
     */
    public function methods_should_override_trait_methods()
    {
        $this->markTestIncomplete('TODO');
    }

    private function analyzeClass($class)
    {
        return (new Analyzer)->analyze(__DIR__ . '/Methods')->getClass($class);
    }
}
