<?php
declare(strict_types = 1);

namespace PhpAnalyzer\Node\Operation;

use PhpAnalyzer\Node\Node;

/**
 * Assignment of the result of an expression to a variable.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class Assign extends Node
{
    /**
     * Can be null if the name is unknown (dynamic).
     *
     * @var string|null
     */
    private $variableName;

    /**
     * The result of the expression is assigned to the variable.
     *
     * @var Node
     */
    private $expression;

    /**
     * @param string|null $variableName
     */
    public function __construct($variableName, Node $expression)
    {
        $this->variableName = $variableName;
        $this->expression = $expression;
    }

    public function toArray() : array
    {
        return [
            'type' => 'assign',
            'variableName' => $this->variableName,
            'expression' => $this->expression->toArray(),
        ];
    }

    public static function fromArray(array $data) : Node
    {
        return new self($data['variableName'], Node::fromArray($data['expression']));
    }

    public static function fromAstNode(\ast\Node $astNode) : Node
    {
        if ($astNode->kind !== \ast\AST_ASSIGN) {
            throw new \Exception('Wrong type: ' . \ast\get_kind_name($astNode->kind));
        }

        $variableName = $astNode->children['var']->children['name'] ?? null;

        return new self($variableName, Node::fromAst($astNode->children['expr']));
    }

    public static function getKind() : int
    {
        return \ast\AST_ASSIGN;
    }
}
