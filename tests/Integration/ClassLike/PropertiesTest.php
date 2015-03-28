<?php

namespace PhpAnalyzer\Test\Integration\ClassLike;

use PhpAnalyzer\Analyzer;
use PhpAnalyzer\Parser\Node\ReflectedProperty;
use PhpAnalyzer\Test\Integration\ClassLike\Properties\BasicClass;
use PhpParser\Node\Stmt\Class_;

class PropertiesTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function should_list_class_properties()
    {
        $class = $this->analyzeClass(BasicClass::class);

        $properties = $class->getProperties();

        $this->assertCount(3, $properties);
        $this->assertProperty($properties, 'public');
    }

    /**
     * @test
     * @dataProvider visibilityProvider
     */
    public function should_list_class_properties_filtered_by_visibility($visibility, $propertyName)
    {
        $class = $this->analyzeClass(BasicClass::class);

        $properties = $class->getProperties($visibility);

        $this->assertCount(1, $properties);
        $this->assertProperty($properties, $propertyName);
    }

    public function visibilityProvider()
    {
        return [
            'public'    => [Class_::MODIFIER_PUBLIC, 'public'],
            'protected' => [Class_::MODIFIER_PROTECTED, 'protected'],
            'private'   => [Class_::MODIFIER_PRIVATE, 'private'],
        ];
    }

    private function analyzeClass($class)
    {
        return (new Analyzer)->analyze(__DIR__ . '/Properties')->getClass($class);
    }

    /**
     * @param ReflectedProperty[] $properties
     * @param string              $name
     */
    private function assertProperty($properties, $name)
    {
        $this->assertArrayHasKey($name, $properties);
        $this->assertInstanceOf(ReflectedProperty::class, $properties[$name]);
        $this->assertEquals($name, $properties[$name]->getName());
    }
}
