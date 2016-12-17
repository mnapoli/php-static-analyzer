<?php
declare(strict_types = 1);

namespace PhpAnalyzer\Node;

use PhpAnalyzer\Scope\Scope;

/**
 * All nodes that don't need a specific class with specific methods.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class GenericNode extends Node
{
    /**
     * @var int
     */
    private $kind;

    public function __construct(int $kind)
    {
        $this->kind = $kind;
    }

    public function toArray() : array
    {
        return [
            'type' => $this->kind,
        ];
    }

    public static function fromArray(array $data, Scope $scope) : Node
    {
        return new self($data['kind']);
    }

    public static function fromAstNode(\ast\Node $astNode, Scope $scope) : Node
    {
        return new self($astNode->kind);
    }

    public function getChildren() : array
    {
        return [];
    }
}
