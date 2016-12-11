<?php
declare(strict_types = 1);

namespace PhpAnalyzer\Node\Operation;

use PhpAnalyzer\Node\Node;

/**
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class Print_ extends Node
{
    /**
     * @var Node
     */
    private $parameter;

    public function __construct(Node $parameter)
    {
        $this->parameter = $parameter;
    }

    public function toArray() : array
    {
        return [
            'type' => 'print',
            'parameter' => $this->parameter->toArray(),
        ];
    }

    public static function fromArray(array $data) : Node
    {
        return new self(Node::fromArray($data['parameter']));
    }

    public static function fromAstNode(\ast\Node $astNode) : Node
    {
        if ($astNode->kind !== \ast\AST_PRINT) {
            throw new \Exception('Wrong type: ' . \ast\get_kind_name($astNode->kind));
        }

        return new self(Node::fromAst($astNode->children['expr']));
    }
}
