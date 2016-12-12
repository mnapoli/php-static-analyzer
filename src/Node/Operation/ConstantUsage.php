<?php
declare(strict_types = 1);

namespace PhpAnalyzer\Node\Operation;

use PhpAnalyzer\Node\Node;

/**
 * Use of a constant.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class ConstantUsage extends Node
{
    /**
     * @var string
     */
    private $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function getChildren() : array
    {
        return [];
    }

    public function toArray() : array
    {
        return [
            'type' => 'constant_usage',
            'name' => $this->name,
        ];
    }

    public static function fromArray(array $data) : Node
    {
        return new self($data['name']);
    }

    public static function fromAstNode(\ast\Node $astNode) : Node
    {
        if ($astNode->kind !== \ast\AST_CONST) {
            throw new \Exception('Wrong type: ' . \ast\get_kind_name($astNode->kind));
        }

        return new self($astNode->children['name']->children['name']);
    }
}
