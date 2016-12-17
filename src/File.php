<?php
declare(strict_types = 1);

namespace PhpAnalyzer;

use PhpAnalyzer\Node\Declaration\Class_;
use PhpAnalyzer\Node\Declaration\Namespace_;
use PhpAnalyzer\Node\Node;
use PhpAnalyzer\Node\NodeList;
use PhpAnalyzer\Scope\Scope;
use PhpAnalyzer\Visitor\Traversable;

/**
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class File implements Traversable
{
    /**
     * @var string
     */
    private $filename;

    /**
     * @var NodeList
     */
    private $tree;

    /**
     * @var Scope
     */
    private $scope;

    public function __construct(Scope $scope, string $filename, Node $tree = null)
    {
        $this->filename = $filename;
        $this->tree = $tree ?: NodeList::fromAstNode(\ast\parse_file($this->filename, 35), $scope);
        $this->scope = $scope;
    }

    public function getNamespace() : Namespace_
    {
        // Returns the first namespace found
        foreach ($this->tree->getChildren() as $node) {
            if ($node instanceof Namespace_) {
                return $node;
            }
        }
        return Namespace_::globalNamespace($this->scope);
    }

    /**
     * @return Class_[]
     */
    public function getClasses() : array
    {
        return array_filter($this->tree->getChildren(), function (Node $node) {
            return $node instanceof Class_;
        });
    }

    public function getChildren() : array
    {
        return [$this->tree];
    }

    public function getClass(string $name) : Class_
    {
        foreach ($this->getClasses() as $class) {
            if ($class->getName() === $name) {
                return $class;
            }
        }
        throw new \Exception("Class $name not found in this file");
    }

    public function toArray() : array
    {
        return [
            'filename' => $this->filename,
            'children' => $this->tree->toArray(),
        ];
    }

    public static function fromArray(array $data, Scope $scope) : self
    {
        if (isset($data['children'])) {
            $tree = Node::fromArray($data['children'], $scope);
        }
        return new self($scope, $data['filename'], $tree ?? null);
    }
}
