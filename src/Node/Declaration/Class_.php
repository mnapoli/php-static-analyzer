<?php
declare(strict_types = 1);

namespace PhpAnalyzer\Node\Declaration;

use ast\Node\Decl;
use PhpAnalyzer\Node\Node;

/**
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class Class_ extends Node
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string|null
     */
    private $docComment;

    /**
     * @var ClassProperty[]
     */
    private $properties;

    /**
     * @var ClassMethod[]
     */
    private $methods;

    /**
     * @var bool
     */
    private $deprecated = false;

    public function __construct(string $name, string $docComment = null, array $properties, array $methods)
    {
        $this->name = $name;
        $this->docComment = $docComment;
        $this->properties = $properties;
        $this->methods = $methods;
    }

    public function getName() : string
    {
        return $this->name;
    }

    public function setNamespace(Namespace_ $namespace)
    {
        $parts = explode('\\', $this->name);
        $namespaceName = $namespace->getName() ? $namespace->getName() . '\\' : '';
        $this->name = $namespaceName . end($parts);
    }

    /**
     * @return null|string
     */
    public function getDocComment()
    {
        return $this->docComment;
    }

    public function isDeprecated() : bool
    {
        return $this->deprecated;
    }

    public function setDeprecated(bool $deprecated)
    {
        $this->deprecated = $deprecated;
    }

    public function getChildren() : array
    {
        return array_merge($this->properties, $this->methods);
    }

    public function toArray() : array
    {
        return [
            'type' => 'class',
            'name' => $this->getName(),
            'docComment' => $this->getDocComment(),
            'deprecated' => $this->isDeprecated(),
            'properties' => array_map(function (ClassProperty $property) {
                return $property->toArray();
            }, $this->properties),
            'methods' => array_map(function (ClassMethod $method) {
                return $method->toArray();
            }, $this->methods),
        ];
    }

    public static function fromArray(array $data) : Node
    {
        $properties = array_map(function ($data) {
            return ClassProperty::fromArray($data);
        }, $data['properties']);
        $methods = array_map(function ($data) {
            return ClassMethod::fromArray($data);
        }, $data['methods']);

        $class = new self($data['name'], $data['docComment'], $properties, $methods);

        if ($data['deprecated']) {
            $class->setDeprecated(true);
        }

        return $class;
    }

    public static function fromAstNode(\ast\Node $astNode) : Node
    {
        if ($astNode->kind !== \ast\AST_CLASS || !$astNode instanceof Decl) {
            throw new \Exception('Wrong type: ' . \ast\get_kind_name($astNode->kind));
        }

        $children = $astNode->children['stmts']->children;

        // Properties
        $properties = array_filter($children, function (\ast\Node $astNode) {
            return $astNode->kind === \ast\AST_PROP_DECL;
        });
        $properties = array_map(function (\ast\Node $astNode) {
            return ClassProperty::fromAstNode($astNode);
        }, $properties);

        // Methods
        $methods = array_filter($children, function (\ast\Node $astNode) {
            return $astNode->kind === \ast\AST_METHOD;
        });
        $methods = array_map(function (\ast\Node $astNode) {
            return ClassMethod::fromAstNode($astNode);
        }, $methods);

        return new self($astNode->name, $astNode->docComment, $properties, $methods);
    }
}
