<?php

namespace PhpAnalyzer;

use PhpAnalyzer\Parser\Node\ReflectedClass;
use PhpAnalyzer\Parser\Node\ReflectedType;
use PhpAnalyzer\Scope\Scope;
use PhpAnalyzer\Scope\Variable;

/**
 * A project contains PHP files.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class Project implements Scope
{
    /**
     * @var File[]
     */
    private $files;

    /**
     * @var ReflectedType[]
     */
    private $classes = [];

    /**
     * @var Variable[]
     */
    private $variables = [];

    public function addFile(File $file)
    {
        $this->files[] = $file;
    }

    /**
     * @return File[]
     */
    public function getFiles()
    {
        return $this->files;
    }

    /**
     * {@inheritdoc}
     */
    public function addClass(ReflectedType $class)
    {
        if ($this->hasClass($class->getFQN())) {
            throw new \LogicException(sprintf('A "%s" class is already defined', $class->getFQN()));
        }

        $this->classes[$class->getFQN()] = $class;
    }

    /**
     * {@inheritdoc}
     */
    public function hasClass($name)
    {
        return isset($this->classes[$name]);
    }

    /**
     * {@inheritdoc}
     */
    public function getClass($name)
    {
        if (! $this->hasClass($name)) {
            throw new \LogicException(sprintf('Class %s not found', $name));
        }

        return $this->classes[$name];
    }

    /**
     * @return ReflectedClass[]
     */
    public function getClasses()
    {
        return $this->classes;
    }

    /**
     * {@inheritdoc}
     */
    public function addVariable(Variable $variable)
    {
        if ($this->hasVariable($variable->getName())) {
            throw new \LogicException(sprintf('A "%s" variable already exist in that scope', $variable->getName()));
        }

        $this->variables[$variable->getName()] = $variable;
    }

    /**
     * {@inheritdoc}
     */
    public function hasVariable($name)
    {
        return isset($this->variables[$name]);
    }

    /**
     * {@inheritdoc}
     */
    public function getVariable($name)
    {
        if (isset($this->variables[$name])) {
            return $this->variables[$name];
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function getVariables()
    {
        return $this->variables;
    }
}
