<?php

namespace PhpAnalyzer\Parser\Node;

use PhpParser\Node;

/**
 * Helper functions to traverse sub-nodes.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
trait SubNodeTraversing
{
    protected function getSubNodes(Node $node)
    {
        $subNodeNames = $node->getSubNodeNames();
        $subNodes = [];

        foreach ($subNodeNames as $name) {
            $subNodes[$name] = $node->$name;
        }

        return $subNodes;
    }
}
