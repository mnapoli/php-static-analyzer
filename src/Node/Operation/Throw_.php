<?php
declare(strict_types = 1);

namespace PhpAnalyzer\Node\Operation;

use PhpAnalyzer\Node\Node;

/**
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class Throw_ extends Node
{
    /**
     * @var Node
     */
    private $expression;

    public function __construct(Node $expression)
    {
        $this->expression = $expression;
    }

    public function toArray() : array
    {
        return [
            'type' => 'throw',
            'expression' => $this->expression->toArray(),
        ];
    }

    public static function fromArray(array $data) : Node
    {
        return new self(Node::fromArray($data['expression']));
    }

    public static function fromAstNode(\ast\Node $astNode) : Node
    {
        if ($astNode->kind !== \ast\AST_THROW) {
            throw new \Exception('Wrong type: ' . \ast\get_kind_name($astNode->kind));
        }

        return new self(Node::fromAst($astNode->children['expr']));
    }
}
