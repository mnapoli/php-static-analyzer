<?php

namespace PhpAnalyzer;

use PhpAnalyzer\Parser\Context;
use PhpAnalyzer\Parser\Visitor\ReflectionVisitor;
use PhpAnalyzer\Parser\Visitor\TypeInferrerVisitor;
use PhpAnalyzer\Scope;
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

        $rootScope = new Scope;
        $context = new Context($rootScope);

        $traverser = new NodeTraverser;
        $traverser->addVisitor(new NameResolver);
        $traverser->traverse($nodes);

        // Create reflection objects
        $traverser = new NodeTraverser;
        $traverser->addVisitor(new ReflectionVisitor($rootScope, $context));
        $traverser->traverse($nodes);

        // Type inference
//        $traverser = new NodeTraverser;
//        $traverser->addVisitor(new TypeInferrerVisitor($context));
//        $traverser->traverse($nodes);

        return $rootScope;
    }
}
