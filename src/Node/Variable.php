<?php
declare(strict_types = 1);

namespace PhpAnalyzer\Node;

/**
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class Variable extends Node
{
    /**
     * @var string
     */
    private $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function toArray() : array
    {
        return [
            'type' => 'variable',
            'name' => $this->name,
        ];
    }

    public static function fromArray(array $data) : Node
    {
        return new self($data['name']);
    }

    public static function fromAstNode(\ast\Node $astNode) : Node
    {
        if ($astNode->kind !== self::getKind()) {
            throw new \Exception('Wrong type: ' . \ast\get_kind_name($astNode->kind));
        }

        $name = $astNode->children['name'];
        if (!is_string($name)) {
            throw new \Exception('Dynamic variable names are not supported yet');
        }

        return new self($name);
    }

    public static function getKind() : int
    {
        return \ast\AST_VAR;
    }
}
