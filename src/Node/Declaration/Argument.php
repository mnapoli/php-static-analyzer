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

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function getName() : string
    {
        return $this->name;
    }

    public function toArray() : array
    {
        return [
            'name' => $this->getName(),
        ];
    }

    public static function fromArray(array $data) : Node
    {
        return new self($data['name']);
    }

    public static function fromAstNode(\ast\Node $astNode) : Node
    {
        if ($astNode->kind !== \ast\AST_PROP_DECL) {
            throw new \Exception('Wrong type: ' . \ast\get_kind_name($astNode->kind));
        }

        $name = $astNode->children[0]->children['name'];

        return new self($name);
    }
}
