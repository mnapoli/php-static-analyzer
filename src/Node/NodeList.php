<?php
declare(strict_types = 1);

namespace PhpAnalyzer\Node;

use const ast\AST_STMT_LIST;

/**
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class NodeList extends Node
{
    /**
     * @var Node[]
     */
    private $children;

    /**
     * @param Node[] $children
     */
    public function __construct(array $children)
    {
        $this->children = $children;
    }

    /**
     * @return Node[]
     */
    public function getChildren() : array
    {
        return $this->children;
    }

    public function toArray() : array
    {
        return [
            'type' => 'list',
            'children' => array_map(function (Node $node) {
                return $node->toArray();
            }, $this->children),
        ];
    }

    public static function fromArray(array $data) : Node
    {
        $children = array_map(function (array $childData) {
            return Node::fromArray($childData);
        }, $data['children']);

        return new self($children);
    }

    public static function fromAstNode(\ast\Node $astNode) : Node
    {
        if ($astNode->kind !== AST_STMT_LIST) {
            throw new \Exception('Wrong type');
        }

        $children = array_map(function (\ast\Node $astNode) {
            return Node::fromAstNode($astNode);
        }, $astNode->children);

        return new self($children);
    }

    public static function getKind() : int
    {
        return \ast\AST_STMT_LIST;
    }
}
