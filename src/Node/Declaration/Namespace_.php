<?php
declare(strict_types = 1);

namespace PhpAnalyzer\Node\Declaration;

use PhpAnalyzer\Node\Node;
use PhpAnalyzer\Scope\Scope;

/**
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class Namespace_ extends Node
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
        $this->name = $name;
        $this->scope = $scope;
    }

    public function getName() : string
    {
        return $this->name;
    }

    public function getChildren() : array
    {
        return [];
    }

    public function toArray() : array
    {
        return [
            'type' => 'namespace',
            'name' => $this->name,
        ];
    }

    public static function fromArray(array $data, Scope $scope) : Node
    {
        return new self($scope, $data['name']);
    }

    public static function fromAstNode(\ast\Node $astNode, Scope $scope) : Node
    {
        if ($astNode->kind !== \ast\AST_NAMESPACE) {
            throw new \Exception('Wrong type: ' . \ast\get_kind_name($astNode->kind));
        }

        return new self($scope, $astNode->children['name']);
    }

    /**
     * Factory to create a instance representing the global (root) namespace.
     */
    public static function globalNamespace(Scope $scope) : self
    {
        return new self($scope, '');
    }
}
