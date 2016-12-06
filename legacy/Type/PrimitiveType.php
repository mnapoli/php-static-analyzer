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

    /**
     * @param string $name
     */
    private function __construct($name)
    {
        $this->name = (string) $name;
    }

    public function toString()
    {
        return $this->name;
    }

    public static function isPrimitiveType($name)
    {
        $name = strtolower($name);

        return isset(self::$map[$name]);
    }

    public static function get($name)
    {
        $name = strtolower($name);

        return new self(self::$map[$name]);
    }
}
