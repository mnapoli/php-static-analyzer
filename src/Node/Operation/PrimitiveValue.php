<?php
declare(strict_types = 1);

namespace PhpAnalyzer\Node\Operation;

use PhpAnalyzer\Node\TypedNode;
use PhpAnalyzer\Node\Node;
use PhpAnalyzer\Type\PrimitiveType;
use PhpAnalyzer\Type\Type;

/**
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class PrimitiveValue extends Node implements TypedNode
{
    /**
     * @var mixed
     */
    private $value;

    /**
     * @var PrimitiveType
     */
    private $returnType;

    public function __construct($value, Type $returnType)
    {
        $this->value = $value;
        $this->returnType = $returnType;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    public function getChildren() : array
    {
        return [];
    }

    public function getReturnType() : Type
    {
        return $this->returnType;
    }

    public function toArray() : array
    {
        return [
            'type' => 'primitive_value',
            'value' => $this->getValue(),
            'returnType' => $this->getReturnType()->toString(),
        ];
    }

    public static function fromArray(array $data) : Node
    {
        return new self($data['value'], PrimitiveType::get($data['returnType']));
    }

    public static function fromValue($value) : PrimitiveValue
    {
        return new self($value, PrimitiveType::getFromValue($value));
    }

    public static function fromAstNode(\ast\Node $astNode) : Node
    {
        throw new \Exception('Invalid case');
    }
}
