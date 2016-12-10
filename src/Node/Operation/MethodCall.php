<?php
declare(strict_types = 1);

namespace PhpAnalyzer\Node\Operation;

use PhpAnalyzer\Node\Node;

/**
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class MethodCall extends Node
{
    /**
     * The expression on which we call the method.
     *
     * @var Node
     */
    private $expression;

    /**
     * @var string
     */
    private $methodName;

    /**
     * @var array
     */
    private $arguments;

    public function __construct(Node $expression, string $methodName, array $arguments)
    {
        $this->expression = $expression;
        $this->methodName = $methodName;
        $this->arguments = $arguments;
    }

    public function toArray() : array
    {
        return [
            'type' => 'method_call',
            'expression' => $this->expression->toArray(),
            'methodName' => $this->methodName,
            'arguments' => array_map(function (Node $argument) {
                return $argument->toArray();
            }, $this->arguments),
        ];
    }

    public static function fromArray(array $data) : Node
    {
        $arguments = array_map(function (array $argument) {
            return Node::fromArray($argument);
        }, $data['arguments']);
        return new self(Node::fromArray($data['expression']), $data['methodName'], $arguments);
    }

    public static function fromAstNode(\ast\Node $astNode) : Node
    {
        if ($astNode->kind !== self::getKind()) {
            throw new \Exception('Wrong type: ' . \ast\get_kind_name($astNode->kind));
        }

        $expression = Node::fromAst($astNode->children['expr']);
        $methodName = $astNode->children['method'];
        $arguments = array_map(function ($astNode) {
            return Node::fromAst($astNode);
        }, $astNode->children['args']->children);

        return new self($expression, $methodName, $arguments);
    }

    public static function getKind() : int
    {
        return \ast\AST_METHOD_CALL;
    }
}
