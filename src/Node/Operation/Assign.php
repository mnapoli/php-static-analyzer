<?php
declare(strict_types = 1);

namespace PhpAnalyzer\Node\Operation;

use PhpAnalyzer\Node\Node;

/**
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class Assign extends Node
{
    public function __construct()
    {
    }

    public function toArray() : array
    {
        return [
            'type' => 'assign',
        ];
    }

    public static function fromArray(array $data) : Node
    {
        return new self();
    }

    public static function fromAstNode(\ast\Node $astNode) : Node
    {
        if ($astNode->kind !== \ast\AST_ASSIGN) {
            throw new \Exception('Wrong type: ' . \ast\get_kind_name($astNode->kind));
        }

        return new self();
    }

    public static function getKind() : int
    {
        return \ast\AST_ASSIGN;
    }
}
