<?php

namespace PhpAnalyzer\Visitor;

use PhpAnalyzer\File;

/**
 * Detects deprecated code.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class DeprecationVisitor extends Visitor
{
    /**
     * @param File $node
     */
    protected function visitNode(Traversable $node)
    {
        assert($node instanceof File);

        foreach ($node->getClasses() as $class) {
            $docblock = $class->getDocComment();
            if (! $docblock) {
                continue;
            }

            if (strpos($docblock, '@deprecated') !== false) {
                $class->setDeprecated(true);
            }
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
