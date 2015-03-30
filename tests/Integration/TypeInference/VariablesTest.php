<?php

namespace PhpAnalyzer\Test\Integration\TypeInference;

use PhpAnalyzer\Scope\LocalVariable;
use PhpAnalyzer\Test\Integration\BaseAnalyzerTest;
use PhpAnalyzer\Type\ClassType;

class VariablesTest extends BaseAnalyzerTest
{
    /**
     * @test
     */
    public function should_infer_variable_type_from_assignment_with_new()
    {
        $project = $this->analyze(__DIR__ . '/Variables');

        $variable = $project->getVariable('assignmentNew');

        $this->assertInstanceOf(LocalVariable::class, $variable);

        /** @var ClassType $type */
        $type = $variable->getType();
        $this->assertInstanceOf(ClassType::class, $type);

        $this->assertEquals('PhpAnalyzer\Test\Integration\TypeInference\Variables\Foo', $type->toString());
    }

    /**
     * @test
     */
    public function should_infer_variable_type_from_assignment_with_another_typed_variable()
    {
        $project = $this->analyze(__DIR__ . '/Variables');

        $variable = $project->getVariable('assignmentVariable');

        $this->assertInstanceOf(LocalVariable::class, $variable);

        /** @var ClassType $type */
        $type = $variable->getType();
        $this->assertInstanceOf(ClassType::class, $type);

        $this->assertEquals('PhpAnalyzer\Test\Integration\TypeInference\Variables\Foo', $type->toString());
    }
}
