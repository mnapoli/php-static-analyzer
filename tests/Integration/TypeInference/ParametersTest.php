<?php

namespace PhpAnalyzer\Test\Integration\TypeInference;

use PhpAnalyzer\Test\Integration\BaseAnalyzerTest;
use PhpAnalyzer\Test\Integration\TypeInference\Parameters\ClassWithMethod;
use PhpAnalyzer\Type\ClassType;

class ParametersTest extends BaseAnalyzerTest
{
    /**
     * @test
     */
    public function should_infer_method_parameter_type_from_type_hint()
    {
        $class = $this->analyzeClass(__DIR__ . '/Parameters', ClassWithMethod::class);

        $method = $class->getMethod('foo');
        $parameter = $method->getParameters()[0];

        /** @var ClassType $type */
        $type = $parameter->getNodeType();
        $this->assertInstanceOf(ClassType::class, $type);

        $this->assertEquals('PhpAnalyzer\Test\Integration\TypeInference\Parameters\SimpleClass', $type->toString());
    }

    /**
     * @test
     */
    public function should_infer_method_parameter_type_from_default_value()
    {
        $this->markTestIncomplete('TODO');
    }

    /**
     * @test
     */
    public function should_infer_method_parameter_type_from_phpdoc()
    {
        $this->markTestIncomplete('TODO');
    }

    /**
     * @test
     */
    public function should_infer_function_parameter_type_from_type_hint()
    {
        $this->markTestIncomplete('TODO');
    }

    /**
     * @test
     */
    public function should_infer_function_parameter_type_from_default_value()
    {
        $this->markTestIncomplete('TODO');
    }

    /**
     * @test
     */
    public function should_infer_function_parameter_type_from_phpdoc()
    {
        $this->markTestIncomplete('TODO');
    }

    /**
     * Merge type-hint + default value + phpdoc
     * @test
     */
    public function should_merge_types_inferred_from_different_sources()
    {
        $this->markTestIncomplete('TODO');
    }
}
