<?php

namespace PhpAnalyzer\Test\Integration\ClassLike;

use PhpAnalyzer\Parser\Node\ReflectedParameter;
use PhpAnalyzer\Test\Integration\BaseAnalyzerTest;
use PhpAnalyzer\Test\Integration\ClassLike\Method\Foo;
use PhpAnalyzer\Type\ClassType;
use PhpAnalyzer\Type\PrimitiveType;

class MethodTest extends BaseAnalyzerTest
{
    /**
     * @test
     */
    public function should_list_method_parameters()
    {
        $class = $this->analyzeClass(__DIR__ . '/Method', Foo::class);

        $method = $class->getMethod('withParameters');
        $parameters = $method->getParameters();

        $this->assertCount(2, $parameters);
        $this->assertParameter(array_shift($parameters), 'param1');
        $this->assertParameter(array_shift($parameters), 'param2');
    }

    /**
     * @test
     */
    public function should_detect_return_type_with_namespaced_class()
    {
        $class = $this->analyzeClass(__DIR__ . '/Method', Foo::class);

        $method = $class->getMethod('returnNamespacedClass');

        $this->assertInstanceOf(ClassType::class, $method->getReturnType());
        $this->assertEquals(Foo::class, $method->getReturnType()->toString());
    }

    /**
     * @test
     */
    public function should_detect_return_type_with_primitive_type()
    {
        $class = $this->analyzeClass(__DIR__ . '/Method', Foo::class);

        $method = $class->getMethod('returnString');

        $this->assertInstanceOf(PrimitiveType::class, $method->getReturnType());
        $this->assertEquals('string', $method->getReturnType()->toString());
    }

    /**
     * @param ReflectedParameter $parameter
     * @param string             $name
     */
    private function assertParameter($parameter, $name)
    {
        $this->assertInstanceOf(ReflectedParameter::class, $parameter);
        $this->assertEquals($name, $parameter->getName());
    }
}
