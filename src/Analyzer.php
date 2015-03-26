<?php

namespace PhpAnalyzer;

use PhpAnalyzer\Parser\Visitor\CallLinkVisitor;
use PhpAnalyzer\Parser\Visitor\LinkToFileVisitor;
use PhpAnalyzer\Parser\Visitor\ReflectionVisitor;
use PhpAnalyzer\Parser\Visitor\TypeInferrerVisitor;
use PhpParser\Lexer;
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
        $project = new Project;

        $finder = new Finder();
        $finder->files()->in($directories)
            ->name('*.php');

        foreach ($finder as $fileInfo) {
            /** @var SplFileInfo $fileInfo */
            try {
                $nodes = $this->parser->parse(file_get_contents($fileInfo));
            } catch (\Exception $e) {
                throw new \RuntimeException(sprintf(
                    'Error while parsing %s: %s', $fileInfo->getRelativePathname(), $e->getMessage()
                ), 0, $e);
            }
            $project->addFile(new File($project, $fileInfo, $nodes));
        }

        $traverser = new ProjectTraverser;

        $traverser->traverse($project, [new NameResolver]);

        // Create reflection objects
        $traverser->traverse($project, [new ReflectionVisitor]);

        // Type inference
        $traverser->traverse($project, [new TypeInferrerVisitor]);

        // Link method calls to called methods
        $traverser->traverse($project, [new CallLinkVisitor]);

        // Link nodes to their file
        $traverser->traverse($project, [new LinkToFileVisitor]);

        return $project;
    }
}
