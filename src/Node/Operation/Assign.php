<?php
declare(strict_types = 1);

namespace PhpAnalyzer\Node\Operation;

use PhpAnalyzer\Node\Node;
use PhpAnalyzer\Scope\Scope;

/**
 * Assignment of the result of an expression to a variable.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class Assign extends Node
{
    /**
     * @var Scope
     */
    private $scope;

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
    public function __construct(Scope $scope, $variableName, Node $expression)
    {
        $this->scope = $scope;
        $this->variableName = $variableName;
        $this->expression = $expression;
    }

    public function getChildren() : array
    {
        return [$this->expression];
    }

    public function toArray() : array
    {
        return [
            'type' => 'assign',
            'variableName' => $this->variableName,
            'expression' => $this->expression->toArray(),
        ];
    }

    public static function fromArray(array $data, Scope $scope) : Node
    {
        return new self($scope, $data['variableName'], Node::fromArray($data['expression'], $scope));
    }

    public static function fromAstNode(\ast\Node $astNode, Scope $scope) : Node
    {
        if ($astNode->kind !== \ast\AST_ASSIGN) {
            throw new \Exception('Wrong type: ' . \ast\get_kind_name($astNode->kind));
        }

        $variableName = $astNode->children['var']->children['name'] ?? null;

        return new self($scope, $variableName, Node::fromAst($astNode->children['expr'], $scope));
    }

    public static function getKind() : int
    {
        return \ast\AST_ASSIGN;
    }
}
