<?php
declare(strict_types = 1);

namespace PhpAnalyzer\Node\Operation;

use PhpAnalyzer\Node\Node;
use PhpAnalyzer\Node\TypedNode;
use PhpAnalyzer\Scope\Scope;
use PhpAnalyzer\Type\ObjectType;
use PhpAnalyzer\Type\Type;

/**
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class New_ extends Node implements TypedNode
{
    /**
     * @var Scope
     */
    private $scope;

    /**
     * @var string
     */
    private $class;

    /**
     * @var Node[]
     */
    private $arguments;

    public function __construct(Scope $scope, string $class, array $arguments)
    {
        $this->scope = $scope;
        $this->class = $class;
        $this->arguments = $arguments;
    }

    public function getChildren() : array
    {
        return $this->arguments;
    }

    public function getReturnType() : Type
    {
        return new ObjectType($this->class);
    }

    public function toArray() : array
    {
        return [
            'type' => 'new',
            'class' => $this->class,
            'arguments' => array_map(function (Node $argument) {
                return $argument->toArray();
            }, $this->arguments),
        ];
    }

    public static function fromArray(array $data, Scope $scope) : Node
    {
        $arguments = array_map(function (array $argument) use ($scope) {
            return Node::fromArray($argument, $scope);
        }, $data['arguments']);
        return new self($scope, $data['class'], $arguments);
    }

    public static function fromAstNode(\ast\Node $astNode, Scope $scope) : Node
    {
        if ($astNode->kind !== \ast\AST_NEW) {
            throw new \Exception('Wrong type: ' . \ast\get_kind_name($astNode->kind));
        }

        $class = $astNode->children['class']->children['name'];
        $arguments = array_map(function ($astNode) use ($scope) {
            return Node::fromAst($astNode, $scope);
        }, $astNode->children['args']->children);

        return new self($scope, $class, $arguments);
    }
}
