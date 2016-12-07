<?php
declare(strict_types = 1);

namespace PhpAnalyzer\Test\Node;

use PhpAnalyzer\Node\PrimitiveValue;

class PrimitiveValueTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     * @dataProvider primitiveTypeProvider
     */
    public function guesses type from value($value, string $expectedType)
    {
        self::assertEquals($expectedType, PrimitiveValue::fromValue($value)->getReturnType()->toString());
    }

    public function primitiveTypeProvider()
    {
        return [
            'int' => [1, 'int'],
            'string' => ['abc', 'string'],
            'float' => [1.23, 'float'],
            'array' => [[], 'array'],
            'null' => [null, 'null'],
            'bool' => [true, 'bool'],
        ];
    }
}
