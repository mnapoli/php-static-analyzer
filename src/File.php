<?php

namespace PhpAnalyzer;

use PhpAnalyzer\Parser\Node\ReflectedType;
use PhpAnalyzer\Scope\Scope;
use PhpAnalyzer\Scope\Variable;
use PhpParser\Node;
use Symfony\Component\Finder\SplFileInfo;

/**
 * A PHP file contains PHP code, i.e. an AST.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class File implements Scope
{
    /**
     * @var Project
     */
    private $project;

    /**
     * @var SplFileInfo
     */
    private $fileInfo;

    /**
     * @var Node[]
     */
    private $nodes = [];

    /**
     * @param Project $project
     * @param SplFileInfo $fileInfo
     * @param Node[] $nodes
     */
    public function __construct(Project $project, SplFileInfo $fileInfo, array $nodes = [])
    {
        $this->project = $project;
        $this->fileInfo = $fileInfo;
        $this->nodes = $nodes;
    }

    /**
     * @return Node[]
     */
    public function getNodes()
    {
        return $this->nodes;
    }

    /**
     * @param Node[] $nodes
     */
    public function replaceNodes(array $nodes = [])
    {
        $this->nodes = $nodes;
    }

    /**
     * {@inheritdoc}
     */
    public function addClass(ReflectedType $class)
    {
        $this->project->addClass($class);
    }

    /**
     * {@inheritdoc}
     */
    public function hasClass($name)
    {
        return $this->project->hasClass($name);
    }

    /**
     * {@inheritdoc}
     */
    public function getClass($name)
    {
        return $this->project->getClass($name);
    }

    /**
     * {@inheritdoc}
     */
    public function getClasses()
    {
        return $this->project->getClasses();
    }

    /**
     * {@inheritdoc}
     */
    public function addVariable(Variable $variable)
    {
        $this->project->addVariable($variable);
    }

    /**
     * {@inheritdoc}
     */
    public function hasVariable($name)
    {
        return $this->project->hasVariable($name);
    }

    /**
     * {@inheritdoc}
     */
    public function getVariable($name)
    {
        return $this->project->getVariable($name);
    }

    /**
     * {@inheritdoc}
     */
    public function getVariables()
    {
        return $this->project->getVariables();
    }
}
