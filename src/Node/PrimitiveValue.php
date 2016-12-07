<?php
declare(strict_types = 1);

namespace PhpAnalyzer\Node;

/**
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class PrimitiveValue extends Node
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
}
