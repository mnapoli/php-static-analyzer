<?php
declare(strict_types = 1);

namespace PhpAnalyzer\Visitor;

use PhpAnalyzer\File;

/**
 * Resolves fully qualified names of:
 *
 * - classes
 * - functions
 * - constants
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class FqnVisitor extends Visitor
{
    /**
     * @param File $node
     */
    protected function visitNode(Traversable $node)
    {
        assert($node instanceof File);

        $namespace = $node->getNamespace();

        foreach ($node->getClasses() as $class) {
            $class->setNamespace($namespace);
        }
    }

    /**
     * @return string[] Class names of the nodes to visit.
     */
    protected function getTargetNodes() : array
    {
        return [File::class];
    }
}
