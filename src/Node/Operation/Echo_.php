<?php
declare(strict_types = 1);

namespace PhpAnalyzer\Node\Operation;

use PhpAnalyzer\Node\Node;
use PhpAnalyzer\Scope\Scope;

/**
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class Echo_ extends Node
{
    /**
     * @var Node
     */
    private $argument;

    public function __construct(Node $argument)
    {
        $this->argument = $argument;
    }

    public function getChildren() : array
    {
        return [$this->argument];
    }

    public function toArray() : array
    {
        return [
            'type' => 'echo',
            'argument' => $this->argument->toArray(),
        ];
    }

    public static function fromArray(array $data, Scope $scope) : Node
    {
        return new self(Node::fromArray($data['argument'], $scope));
    }

    public static function fromAstNode(\ast\Node $astNode, Scope $scope) : Node
    {
        if ($astNode->kind !== \ast\AST_ECHO) {
            throw new \Exception('Wrong type: ' . \ast\get_kind_name($astNode->kind));
        }

        return new self(Node::fromAst($astNode->children['expr'], $scope));
    }
}
