<?php

namespace PhpAnalyzer\Node;

use PhpAnalyzer\Node\Operation\Assign;

/**
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
abstract class Node
{
    /**
     * Used to serialize the node.
     */
    abstract public function toArray() : array;

    public static function fromArray(array $data) : Node
    {
        switch ($data['type']) {
            case 'list':
                return NodeList::fromArray($data);
            case 'class':
                return Class_::fromArray($data);
            case 'namespace':
                return Namespace_::fromArray($data);
            case 'assign':
                return Assign::fromArray($data);
            default:
                throw new \Exception('Unknown node type ' . $data['type']);
        }
    }

    public static function fromAstNode(\ast\Node $astNode) : Node
    {
        switch ($astNode->kind) {
            case \ast\AST_STMT_LIST:
                return NodeList::fromAstNode($astNode);
            case \ast\AST_CLASS:
                return Class_::fromAstNode($astNode);
            case \ast\AST_NAMESPACE:
                return Namespace_::fromAstNode($astNode);
            case \ast\AST_ASSIGN:
                return Assign::fromAstNode($astNode);
            default:
                return GenericNode::fromAstNode($astNode);
        }
    }
}
