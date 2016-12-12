<?php
declare(strict_types = 1);

namespace PhpAnalyzer\Node\Operation;

use PhpAnalyzer\Node\Node;
use PhpAnalyzer\Node\TypedNode;
use PhpAnalyzer\Type\ObjectType;
use PhpAnalyzer\Type\Type;

/**
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class New_ extends Node implements TypedNode
{
    /**
     * @var string
     */
    private $class;

    /**
     * @var Node[]
     */
    private $arguments;

    public function __construct(string $class, array $arguments)
    {
        $this->class = $class;
        $this->arguments = $arguments;
    }

    public function getChildren() : array
    {
        return $this->arguments;
    }

    public function getReturnType() : Type
    {
        return new ObjectType($this->class);
    }

    public function toArray() : array
    {
        return [
            'type' => 'new',
            'class' => $this->class,
            'arguments' => array_map(function (Node $argument) {
                return $argument->toArray();
            }, $this->arguments),
        ];
    }

    public static function fromArray(array $data) : Node
    {
        $arguments = array_map(function (array $argument) {
            return Node::fromArray($argument);
        }, $data['arguments']);
        return new self($data['class'], $arguments);
    }

    public static function fromAstNode(\ast\Node $astNode) : Node
    {
        if ($astNode->kind !== \ast\AST_NEW) {
            throw new \Exception('Wrong type: ' . \ast\get_kind_name($astNode->kind));
        }

        $class = $astNode->children['class']->children['name'];
        $arguments = array_map(function ($astNode) {
            return Node::fromAst($astNode);
        }, $astNode->children['args']->children);

        return new self($class, $arguments);
    }
}
