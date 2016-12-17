<?php
declare(strict_types = 1);

namespace PhpAnalyzer\Node\Declaration;

use PhpAnalyzer\Node\Node;
use PhpAnalyzer\Scope\Scope;

/**
 * Method or function argument.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class Argument extends Node
{
    /**
     * @var Scope
     */
    private $scope;

    /**
     * @var string
     */
    private $name;

    /**
     * @var Node|null
     */
    private $defaultValue;

    public function __construct(Scope $scope, string $name, Node $defaultValue = null)
    {
        $this->scope = $scope;
        $this->name = $name;
        $this->defaultValue = $defaultValue;
    }

    public function getName() : string
    {
        return $this->name;
    }

    public function getChildren() : array
    {
        return [$this->defaultValue];
    }

    public function toArray() : array
    {
        return [
            'name' => $this->getName(),
            'defaultValue' => $this->defaultValue ? $this->defaultValue->toArray() : null,
        ];
    }

    public static function fromArray(array $data, Scope $scope) : Node
    {
        return new self(
            $scope,
            $data['name'],
            $data['defaultValue'] ? Node::fromArray($data['defaultValue'], $scope) : null
        );
    }

    public static function fromAstNode(\ast\Node $astNode, Scope $scope) : Node
    {
        if ($astNode->kind !== \ast\AST_PARAM) {
            throw new \Exception('Wrong type: ' . \ast\get_kind_name($astNode->kind));
        }

        $name = $astNode->children['name'];
        $defaultValue = $astNode->children['default'];
        if ($defaultValue) {
            $defaultValue = Node::fromAst($defaultValue, $scope);
        }

        return new self($scope, $name, $defaultValue);
    }
}
