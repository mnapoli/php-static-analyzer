<?php
declare(strict_types = 1);

namespace PhpAnalyzer\Node\Declaration;

use PhpAnalyzer\Node\Node;

/**
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class Namespace_ extends Node
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

    public static function fromArray(array $data) : Node
    {
        return new self($data['name']);
    }

    public static function fromAstNode(\ast\Node $astNode) : Node
    {
        if ($astNode->kind !== \ast\AST_NAMESPACE) {
            throw new \Exception('Wrong type: ' . \ast\get_kind_name($astNode->kind));
        }

        return new self($astNode->children['name']);
    }

    /**
     * Factory to create a instance representing the global (root) namespace.
     */
    public static function globalNamespace() : self
    {
        return new self('');
    }
}
