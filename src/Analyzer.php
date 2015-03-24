<?php

namespace PhpAnalyzer;

use PhpAnalyzer\Parser\Context;
use PhpAnalyzer\Parser\Visitor\CallLinkVisitor;
use PhpAnalyzer\Parser\Visitor\ReflectionVisitor;
use PhpAnalyzer\Parser\Visitor\TypeInferrerVisitor;
use PhpAnalyzer\Scope\Scope;
use PhpParser\Lexer;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitor\NameResolver;
use PhpParser\Parser;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

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
    }

    public function analyze($directories)
    {
        $finder = new Finder();
        $finder->files()->in($directories)
            ->name('*.php');

        $nodes = [];
        foreach ($finder as $file) {
            /** @var SplFileInfo $file */
            try {
                // TODO do not merge
                $nodes = array_merge(
                    $this->parser->parse(file_get_contents($file)),
                    $nodes
                );
            } catch (\Exception $e) {
                throw new \RuntimeException(sprintf('Error while parsing %s: %s', $file->getRelativePathname(), $e->getMessage()), 0, $e);
            }
        }

        $rootScope = new Scope;
        $context = new Context($rootScope);

        $traverser = new NodeTraverser(false);
        $traverser->addVisitor(new NameResolver);
        $nodes = $traverser->traverse($nodes);

        // Create reflection objects
        $traverser = new NodeTraverser(false);
        $traverser->addVisitor(new ReflectionVisitor($rootScope, $context));
        $nodes = $traverser->traverse($nodes);

        // Type inference
        $traverser = new NodeTraverser(false);
        $traverser->addVisitor(new TypeInferrerVisitor($context));
        $nodes = $traverser->traverse($nodes);

        // Link method calls to called methods
        $traverser = new NodeTraverser(false);
        $traverser->addVisitor(new CallLinkVisitor);
        $traverser->traverse($nodes);

        return $rootScope;
    }
}
