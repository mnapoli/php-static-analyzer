<?php
declare(strict_types = 1);

namespace PhpAnalyzer\Node;

use PhpAnalyzer\Type\PrimitiveType;
use PhpAnalyzer\Type\Type;

/**
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class PrimitiveValue extends Node implements HasType
{
    /**
     * @var mixed
     */
    private $value;

    public function __construct($value)
    {
        $this->value = $value;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    public function getReturnType() : Type
    {
        $type = gettype($this->value);
        switch ($type) {
            case 'integer':
            case 'string':
            case 'double':
            case 'array':
            case 'NULL':
            case 'boolean':
                return PrimitiveType::get($type);
            default:
                throw new \Exception('Unsupported primitive type ' . $type);
        }
    }

    public function toArray() : array
    {
        return [
            'type' => 'primitive_value',
            'value' => $this->getValue(),
        ];
    }

    public static function fromArray(array $data) : Node
    {
        return new self($data['value']);
    }

    public static function fromAstNode(\ast\Node $astNode) : Node
    {
        throw new \Exception('Invalid case');
    }
}
