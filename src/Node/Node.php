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
        'primitive_value' => PrimitiveValue::class,
    ];

    /**
     * Used to serialize the node.
     */
    abstract public function toArray() : array;

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

    abstract public static function fromAstNode(\ast\Node $astNode) : Node;

    /**
     * Create a node from anything found in the AST of the PHP-AST extension.
     *
     * @param mixed $anything
     */
    public static function fromAst($anything) : Node
    {
        if (is_scalar($anything)) {
            return new PrimitiveValue($anything);
        }
        if (! $anything instanceof \ast\Node) {
            throw new \Exception('Unknown node');
        }

        $allNodes = self::AST_TO_NODES;

        if (! isset($allNodes[$anything->kind])) {
            $class = GenericNode::class;
        } else {
            $class = $allNodes[$anything->kind];
        }

        return $class::fromAstNode($anything);
    }
}
