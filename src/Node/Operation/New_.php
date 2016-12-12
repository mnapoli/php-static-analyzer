<?php
declare(strict_types = 1);

namespace PhpAnalyzer\Node\Operation;

use PhpAnalyzer\Node\Node;

/**
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class New_ extends Node
{
    /**
     * @var Node
     */
    private $class;

    /**
     * @var Node[]
     */
    private $arguments;

    public function __construct(Node $class, array $arguments)
    {
        $this->class = $class;
        $this->arguments = $arguments;
    }

    public function getChildren() : array
    {
        return array_merge([$this->class], $this->arguments);
    }

    public function toArray() : array
    {
        return [
            'type' => 'new',
            'class' => $this->class->toArray(),
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
        return new self(Node::fromArray($data['class']), $arguments);
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

        return new self(Node::fromAst($class), $arguments);
    }
}
