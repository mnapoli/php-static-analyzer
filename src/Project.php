<?php
declare(strict_types = 1);

namespace PhpAnalyzer;

use PhpAnalyzer\Node\Declaration\Class_;
use PhpAnalyzer\Scope\GlobalScope;
use PhpAnalyzer\Scope\Scope;
use PhpAnalyzer\Visitor\Traversable;
use PhpAnalyzer\Visitor\Visitor;
use Symfony\Component\Finder\Finder;

/**
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class Project implements Traversable
{
    /**
     * @var string[]
     */
    private $directories;

    /**
     * @var File[]
     */
    private $files;

    /**
     * @var GlobalScope
     */
    private $globalScope;

    /**
     * @param string[] $directories Directories containing code to parse.
     * @param Visitor[] $visitors Visitors to automatically apply.
     */
    public function __construct(array $directories, array $visitors = [])
    {
        $this->directories = $directories;
        $this->globalScope = new GlobalScope($this);

        $this->parseDirectories();

        foreach ($visitors as $visitor) {
            $visitor->visit($this);
        }
    }

    /**
     * @return Class_[]
     */
    public function getClasses() : array
    {
        $classes = [];
        foreach ($this->files as $file) {
            $classes = array_merge($classes, $file->getClasses());
        }
        return $classes;
    }

    public function hasClass(string $className) : bool
    {
        // TODO optimize
        $classes = $this->getClasses();
        foreach ($classes as $class) {
            if ($class->getName() === $className) {
                return true;
            }
        }
        return false;
    }

    public function getClass(string $className) : Class_
    {
        // TODO optimize
        $classes = $this->getClasses();
        foreach ($classes as $class) {
            if ($class->getName() === $className) {
                return $class;
            }
        }
        throw new \Exception("Class $className not found");
    }

    public function getGlobalScope() : Scope
    {
        return $this->globalScope;
    }

    public function getChildren() : array
    {
        return $this->files;
    }

    private function parseDirectories()
    {
        $finder = new Finder();
        $finder->name('*.php')
            ->in($this->directories);

        $this->files = [];
        foreach ($finder as $fileInfo) {
            $this->files[] = new File($this->getGlobalScope(), $fileInfo->getPathname());
        }
    }
}
