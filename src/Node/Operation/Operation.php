<?php
declare(strict_types = 1);

namespace PhpAnalyzer\Node\Operation;

use PhpAnalyzer\Node\Node;

/**
 * Binary or numerical operation.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class Operation extends Node
{
    /**
     * @var Operand
     */
    private $operand;

    /**
     * @var Node
     */
    private $left;

    /**
     * @var Node
     */
    private $right;

    public function __construct(Operand $operand, Node $left, Node $right)
    {
        $this->operand = $operand;
        $this->left = $left;
        $this->right = $right;
    }

    public function getOperand() : Operand
    {
        return $this->operand;
    }

    public function getLeft() : Node
    {
        return $this->left;
    }

    public function getRight() : Node
    {
        return $this->right;
    }

    public function getChildren() : array
    {
        return [$this->left, $this->right];
    }

    public function toArray() : array
    {
        return [
            'type' => 'operation',
            'operand' => $this->operand->getValue(),
            'left' => $this->left->toArray(),
            'right' => $this->right->toArray(),
        ];
    }

    public static function fromArray(array $data) : Node
    {
        return new self(
            new Operand($data['operand']),
            Node::fromArray($data['left']),
            Node::fromArray($data['right'])
        );
    }

    public static function fromAstNode(\ast\Node $astNode) : Node
    {
        if ($astNode->kind !== self::getKind()) {
            throw new \Exception('Wrong type: ' . \ast\get_kind_name($astNode->kind));
        }

        return new self(
            Operand::fromAstNode($astNode),
            Node::fromAst($astNode->children['left']),
            Node::fromAst($astNode->children['right'])
        );
    }

    public static function getKind() : int
    {
        return \ast\AST_BINARY_OP;
    }
}
