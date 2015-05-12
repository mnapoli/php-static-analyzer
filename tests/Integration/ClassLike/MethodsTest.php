<?php

namespace PhpAnalyzer\Test\Integration\ClassLike;

use PhpAnalyzer\Parser\Node\ReflectedMethod;
use PhpAnalyzer\Test\Integration\BaseAnalyzerTest;
use PhpAnalyzer\Test\Integration\ClassLike\Methods\BasicClass;
use PhpAnalyzer\Test\Integration\ClassLike\Methods\Implementation;
use PhpAnalyzer\Test\Integration\ClassLike\Methods\SubClass;
use PhpParser\Node\Stmt\Class_;

class MethodsTest extends BaseAnalyzerTest
{
    /**
     * @test
     */
    public function should_list_class_methods()
    {
        $class = $this->analyzeClass(__DIR__ . '/Methods', BasicClass::class);

        $methods = $class->getMethods();

        $this->assertCount(3, $methods);
        $this->assertMethod($methods, 'publicMethod');
        $this->assertMethod($methods, 'protectedMethod');
        $this->assertMethod($methods, 'privateMethod');
    }

    /**
     * @test
     * @dataProvider visibilityProvider
     */
    public function should_list_class_methods_filtered_by_visibility($visibility, $methodName)
    {
        $class = $this->analyzeClass(__DIR__ . '/Methods', BasicClass::class);

        $methods = $class->getMethods($visibility);

        $this->assertCount(1, $methods);
        $this->assertMethod($methods, $methodName);
    }

    public function visibilityProvider()
    {
        return [
            'public'    => [Class_::MODIFIER_PUBLIC, 'publicMethod'],
            'protected' => [Class_::MODIFIER_PROTECTED, 'protectedMethod'],
            'private'   => [Class_::MODIFIER_PRIVATE, 'privateMethod'],
        ];
    }

    /**
     * @test
     */
    public function methods_should_include_parent_methods()
    {
        $class = $this->analyzeClass(__DIR__ . '/Methods', SubClass::class);

        $methods = $class->getMethods();

        $this->assertCount(5, $methods);
        $this->assertMethod($methods, 'publicMethod');
        $this->assertMethod($methods, 'protectedMethod');
        $this->assertMethod($methods, 'publicMethod2');
        $this->assertMethod($methods, 'protectedMethod2');
        $this->assertMethod($methods, 'privateMethod2');
    }

    /**
     * @test
     */
    public function methods_should_include_interfaces_methods()
    {
        $class = $this->analyzeClass(__DIR__ . '/Methods', Implementation::class);

        $methods = $class->getMethods();

        $this->assertCount(2, $methods);
        $this->assertMethod($methods, 'publicMethod');
        $this->assertMethod($methods, 'publicMethod2');
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
     *
     * @link http://php.net/manual/en/language.oop5.traits.php#language.oop5.traits.precedence
     */
    public function methods_should_follow_php_precedence_rules()
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

    /**
     * @param ReflectedMethod[] $methods
     * @param string $name
     */
    private function assertMethod(array $methods, $name)
    {
        $this->assertArrayHasKey($name, $methods);
        $this->assertInstanceOf(ReflectedMethod::class, $methods[$name]);
        $this->assertEquals($name, $methods[$name]->getName());
    }
}
