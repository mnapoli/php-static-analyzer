<?php
declare(strict_types = 1);

namespace PhpAnalyzer\Node\Operation;

use PhpAnalyzer\Node\Node;
use PhpAnalyzer\Node\TypedNode;
use PhpAnalyzer\Scope\LocalVariable;
use PhpAnalyzer\Scope\Scope;
use PhpAnalyzer\Type\Type;

/**
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class Variable extends Node implements TypedNode
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

        // Register the variable in the current scope
        if ($this->scope->hasVariable($this->getName())) {
            // TODO merge type
            return;
        }
        $this->scope->addVariable(new LocalVariable($this));
    }

    public function getName() : string
    {
        return $this->name;
    }

    public function getReturnType() : Type
    {
        return $this->scope->getVariable($this->getName())->getType();
    }

    public function getChildren() : array
    {
        return [];
    }

    public function toArray() : array
    {
        return [
            'type' => 'variable',
            'name' => $this->name,
        ];
    }

    public static function fromArray(array $data, Scope $scope) : Node
    {
        return new self($scope, $data['name']);
    }

    public static function fromAstNode(\ast\Node $astNode, Scope $scope) : Node
    {
        if ($astNode->kind !== self::getKind()) {
            throw new \Exception('Wrong type: ' . \ast\get_kind_name($astNode->kind));
        }

        $name = $astNode->children['name'];
        if (!is_string($name)) {
            throw new \Exception('Dynamic variable names are not supported yet');
        }

        return new self($scope, $name);
    }

    public static function getKind() : int
    {
        return \ast\AST_VAR;
    }
}
