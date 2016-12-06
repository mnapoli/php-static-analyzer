<?php

namespace PhpAnalyzer\Parser\Visitor;

use PhpAnalyzer\Parser\Node\Node;
use PhpParser\Node;
use PhpParser\NodeVisitorAbstract;

/**
 * Detects deprecated code.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class DeprecationVisitor extends NodeVisitorAbstract
{
    public function enterNode(Node $node)
    {
        $docblock = $node->getDocComment();

        if (! $docblock) {
            return;
        }

        if (strpos($docblock->getText(), '@deprecated') !== false) {
            $node->setAttribute('deprecated', true);
        }
    }
}
