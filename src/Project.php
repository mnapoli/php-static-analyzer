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
     * @param string[] $directories
     * @param Visitor[] $visitors
     */
    public function __construct(array $directories, array $visitors = [])
    {
        $this->directories = $directories;
        $this->globalScope = new GlobalScope($this);

        $this->parseDirectories();

        foreach ($visitors as $visitor) {
            foreach ($this->files as $file) {
                $visitor->visit($file);
            }
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
            $this->files[] = new File($fileInfo->getPathname());
        }
    }
}
