<?php

namespace PhpAnalyzer\Test\Integration\ClassLike;

use PhpAnalyzer\Analyzer;
use PhpAnalyzer\Parser\Node\ReflectedProperty;
use PhpAnalyzer\Test\Integration\ClassLike\Properties\BasicClass;
use PhpAnalyzer\Test\Integration\ClassLike\Properties\SubClass;
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
        $this->assertProperty($properties, 'protected');
        $this->assertProperty($properties, 'private');
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

    /**
     * @test
     */
    public function should_include_parent_properties_in_property_list()
    {
        $class = $this->analyzeClass(SubClass::class);

        $properties = $class->getProperties();

        $this->assertCount(5, $properties);
        $this->assertProperty($properties, 'public');
        $this->assertProperty($properties, 'protected');
        $this->assertProperty($properties, 'public2');
        $this->assertProperty($properties, 'protected2');
        $this->assertProperty($properties, 'private2');
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
