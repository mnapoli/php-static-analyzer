<?php
declare(strict_types = 1);

namespace PhpAnalyzer;

use PhpAnalyzer\Node\Declaration\Class_;
use PhpAnalyzer\Node\Declaration\Namespace_;
use PhpAnalyzer\Node\Node;
use PhpAnalyzer\Node\NodeList;

/**
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class File
{
    /**
     * @var string
     */
    private $filename;

    /**
     * @var NodeList
     */
    private $tree;

    public function __construct(string $filename, Node $tree = null)
    {
        $this->filename = $filename;
        $this->tree = $tree ?: NodeList::fromAstNode(\ast\parse_file($this->filename, 35));
    }

    public function getNamespace() : Namespace_
    {
        // Returns the first namespace found
        foreach ($this->tree->getChildren() as $node) {
            if ($node instanceof Namespace_) {
                return $node;
            }
        }
        return Namespace_::globalNamespace();
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

    public static function fromArray(array $data) : self
    {
        if (isset($data['children'])) {
            $tree = Node::fromArray($data['children']);
        }
        return new self($data['filename'], $tree ?? null);
    }
}
