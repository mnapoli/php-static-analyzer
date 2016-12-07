<?php

namespace PhpAnalyzer\Node;

use PhpAnalyzer\Node\Operation\Assign;

/**
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
abstract class Node
{
    const AST_TO_NODES = [
        \ast\AST_STMT_LIST => NodeList::class,
        \ast\AST_CLASS => Class_::class,
        \ast\AST_NAMESPACE => Namespace_::class,
        \ast\AST_ASSIGN => Assign::class,
    ];
    const TYPE_TO_NODES = [
        'list' => NodeList::class,
        'class' => Class_::class,
        'namespace' => Namespace_::class,
        'assign' => Assign::class,
    ];

    /**
     * Used to serialize the node.
     */
    abstract public function toArray() : array;

    /**
     * The "kind" of node as defined by the PHP-AST extension.
     */
    abstract public static function getKind() : int;

    public static function fromArray(array $data) : Node
    {
        $allNodes = self::TYPE_TO_NODES;

        if (!isset($allNodes[$data['type']])) {
            $class = GenericNode::class;
        } else {
            $class = $allNodes[$data['type']];
        }

        return $class::fromArray($data);
    }

    public static function fromAstNode(\ast\Node $astNode) : Node
    {
        $allNodes = self::AST_TO_NODES;

        if (!isset($allNodes[$astNode->kind])) {
            $class = GenericNode::class;
        } else {
            $class = $allNodes[$astNode->kind];
        }

        return $class::fromAstNode($astNode);
    }
}
