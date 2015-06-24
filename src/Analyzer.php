<?php

namespace PhpAnalyzer;

use PhpAnalyzer\Log\Logger;
use PhpAnalyzer\Parser\Visitor\CallLinkVisitor;
use PhpAnalyzer\Parser\Visitor\DeprecationVisitor;
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

    public function analyze($directories, $cacheFile = null)
    {
        if ($cacheFile && file_exists($cacheFile)) {
            return unserialize(file_get_contents($cacheFile));
        }

        $project = new Project;

        $finder = new Finder();
        $finder->files()->in($directories)
            ->name('*.php');

        Logger::info('Parsing {count} files', ['count' => count($finder)]);

        foreach ($finder as $fileInfo) {
            /** @var SplFileInfo $fileInfo */
            try {
                $nodes = $this->parser->parse(file_get_contents($fileInfo));
            } catch (\Exception $e) {
                throw new \RuntimeException(sprintf(
                    'Error while parsing %s: %s', $fileInfo->getRelativePathname(), $e->getMessage()
                ), 0, $e);
            }
            $project->addFile(new File($project, $fileInfo->getRelativePathname(), $nodes));
        }

        $traverser = new ProjectTraverser;

        Logger::info('Resolving fully qualified names');
        $traverser->traverse($project, [new NameResolver]);

        // Create reflection objects
        Logger::info('Creating reflection objects');
        $traverser->traverse($project, [new ReflectionVisitor]);

        // Type inference
        Logger::info('Inferring types');
        $traverser->traverse($project, [new TypeInferrerVisitor]);

        Logger::info('Linking nodes');
        $traverser->traverse($project, [
            // Link method calls to called methods
            new CallLinkVisitor,
            // Link nodes to their file
            new LinkToFileVisitor,
            // Detect deprecated code
            new DeprecationVisitor,
        ]);

        if ($cacheFile) {
            Logger::info('Caching the project');
            $serialized = serialize($project);
            file_put_contents($cacheFile, $serialized);
        }

        return $project;
    }
}
