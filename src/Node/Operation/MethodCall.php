<?php
declare(strict_types = 1);

namespace PhpAnalyzer\Node\Operation;

use PhpAnalyzer\Node\Declaration\ClassMethod;
use PhpAnalyzer\Node\Node;
use PhpAnalyzer\Scope\Scope;
use PhpAnalyzer\Type\ObjectType;

/**
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class MethodCall extends Node
{
    /**
     * @var Scope
     */
    private $scope;

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
     * @var Node[]
     */
    private $arguments;

    public function __construct(Scope $scope, Node $expression, string $methodName, array $arguments)
    {
        $this->scope = $scope;
        $this->expression = $expression;
        $this->methodName = $methodName;
        $this->arguments = $arguments;
    }

    /**
     * @return ClassMethod|null Null if the class name is unknown/dynamic.
     */
    public function getMethodCalled(Scope $scope)
    {
        if (! $this->expression instanceof Variable) {
            return null;
        }

        $variableName = $this->expression->getName();
        if (!$scope->hasVariable($variableName)) {
            return null;
        }

        $variable = $scope->getVariable($variableName);

        $type = $variable->getType();
        if (!$type instanceof ObjectType) {
            // We can't call a method on something that is not an object
            return null;
        }
        $className = $type->toString();

        if (! $scope->hasClass($className)) {
            // Calling an unknown class
            return null;
        }
        $class = $scope->getClass($className);

        if (! $class->hasMethod($this->getMethodName())) {
            // Calling an unknown method
            return null;
        }

        return $class->getMethod($this->getMethodName());
    }

    public function getMethodName() : string
    {
        return $this->methodName;
    }

    public function getChildren() : array
    {
        return $this->arguments;
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

    public static function fromArray(array $data, Scope $scope) : Node
    {
        $arguments = array_map(function (array $argument) use ($scope) {
            return Node::fromArray($argument, $scope);
        }, $data['arguments']);
        return new self($scope, Node::fromArray($data['expression'], $scope), $data['methodName'], $arguments);
    }

    public static function fromAstNode(\ast\Node $astNode, Scope $scope) : Node
    {
        if ($astNode->kind !== self::getKind()) {
            throw new \Exception('Wrong type: ' . \ast\get_kind_name($astNode->kind));
        }

        $expression = Node::fromAst($astNode->children['expr'], $scope);
        $methodName = $astNode->children['method'];
        $arguments = array_map(function ($astNode) use ($scope) {
            return Node::fromAst($astNode, $scope);
        }, $astNode->children['args']->children);

        return new self($scope, $expression, $methodName, $arguments);
    }

    public static function getKind() : int
    {
        return \ast\AST_METHOD_CALL;
    }
}
