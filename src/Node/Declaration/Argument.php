<?php
declare(strict_types = 1);

namespace PhpAnalyzer\Node\Declaration;

use PhpAnalyzer\Node\Node;

/**
 * Method or function argument.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class Argument extends Node
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var Node|null
     */
    private $defaultValue;

    public function __construct(string $name, Node $defaultValue = null)
    {
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

    public static function fromArray(array $data) : Node
    {
        return new self(
            $data['name'],
            $data['defaultValue'] ? Node::fromArray($data['defaultValue']) : null
        );
    }

    public static function fromAstNode(\ast\Node $astNode) : Node
    {
        if ($astNode->kind !== \ast\AST_PARAM) {
            throw new \Exception('Wrong type: ' . \ast\get_kind_name($astNode->kind));
        }

        $name = $astNode->children['name'];
        $defaultValue = $astNode->children['default'];
        if ($defaultValue) {
            $defaultValue = Node::fromAst($defaultValue);
        }

        return new self($name, $defaultValue);
    }
}
