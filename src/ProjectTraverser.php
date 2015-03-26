<?php

namespace PhpAnalyzer;

use PhpAnalyzer\Parser\Context;
use PhpAnalyzer\Visitor\ProjectVisitor;
use PhpParser\NodeTraverser;

/**
 * Traverses a project's nodes.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class ProjectTraverser
{
    public function traverse(Project $project, array $visitors)
    {
        $traverser = new NodeTraverser(false);

        foreach ($visitors as $visitor) {
            $traverser->addVisitor($visitor);
        }

        foreach ($project->getFiles() as $file) {
            foreach ($visitors as $visitor) {
                if ($visitor instanceof ProjectVisitor) {
                    $visitor->setFile($file);
                    $visitor->setContext(new Context($file));
                }
            }

            $nodes = $traverser->traverse($file->getNodes());
            $file->replaceNodes($nodes);
        }
    }
}
