<?php
declare(strict_types = 1);

namespace PhpAnalyzer\Node\Operation;

use PhpAnalyzer\Node\Node;
use PhpAnalyzer\Scope\Scope;

/**
 * Use of a constant.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class ConstantUsage extends Node
{
    /**
     * @var Scope
     */
    private $scope;

    /**
     * @var string
     */
    private $name;

    public function __construct(Scope $scope, string $name)
    {
        $this->scope = $scope;
        $this->name = $name;
    }

    public function getChildren() : array
    {
        return [];
    }

    public function toArray() : array
    {
        return [
            'type' => 'constant_usage',
            'name' => $this->name,
        ];
    }

    public static function fromArray(array $data, Scope $scope) : Node
    {
        return new self($scope, $data['name']);
    }

    public static function fromAstNode(\ast\Node $astNode, Scope $scope) : Node
    {
        if ($astNode->kind !== \ast\AST_CONST) {
            throw new \Exception('Wrong type: ' . \ast\get_kind_name($astNode->kind));
        }

        return new self($scope, $astNode->children['name']->children['name']);
    }
}
