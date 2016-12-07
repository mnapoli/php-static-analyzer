<?php

namespace PhpAnalyzer\Type;

/**
 * Primitive PHP type, e.g. string, int, float, bool, array.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class PrimitiveType implements Type
{
    private static $map = [
        'string'  => 'string',
        'int'     => 'int',
        'integer' => 'int',
        'float'   => 'float',
        'double'  => 'float',
        'bool'    => 'bool',
        'boolean' => 'bool',
        'array'   => 'array',
    ];

    /**
     * @var string
     */
    private $name;

    private function __construct(string $name)
    {
        $this->name = $name;
    }

    public function toString() : string
    {
        return $this->name;
    }

    public static function isPrimitiveType(string $name) : bool
    {
        $name = strtolower($name);

        return isset(self::$map[$name]);
    }

    public static function get(string $name) : string
    {
        $name = strtolower($name);

        return new self(self::$map[$name]);
    }
}
