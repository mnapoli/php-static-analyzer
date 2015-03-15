<?php

namespace PhpAnalyzer;

use PhpAnalyzer\Parser\Visitor\DeclarationVisitor;
use PhpAnalyzer\Reflection\Registry;
use PhpParser\Lexer;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitor\NameResolver;
use PhpParser\Parser;
use Symfony\Component\Finder\Finder;

/**
 * PHP analyzer.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class Analyzer
{
    /**
     * @var Parser
     */
    private $parser;

    public function __construct()
    {
        $this->parser = new Parser(new Lexer);
        $this->traverser = new NodeTraverser;
    }

    public function analyze($directory)
    {
        $finder = new Finder();
        $finder->files()->in($directory);

        $nodes = [];
        foreach ($finder as $file) {
            $nodes = array_merge(
                $this->parser->parse(file_get_contents($file)),
                $nodes
            );
        }

        $registry = new Registry;

        $this->traverser->addVisitor(new NameResolver);
        $this->traverser->addVisitor(new DeclarationVisitor($registry));

        $this->traverser->traverse($nodes);

        return $registry;
    }
}
