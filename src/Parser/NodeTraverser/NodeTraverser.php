<?php

namespace PhpAnalyzer\Parser\NodeTraverser;

use PhpParser\Node;
use PhpParser\NodeVisitor;

/**
 * Node traverser that doesn't clone all nodes.
 *
 * That allows to traverse nodes, replace some of them but still maintain references between them
 * (as long as replaced nodes are not referenced).
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class NodeTraverser extends \PhpParser\NodeTraverser
{
    protected function traverseNode(Node $node)
    {
        foreach ($node->getSubNodeNames() as $name) {
            $subNode =& $node->$name;

            if (is_array($subNode)) {
                $subNode = $this->traverseArray($subNode);
            } elseif ($subNode instanceof Node) {
                $traverseChildren = true;
                foreach ($this->visitors as $visitor) {
                    $return = $visitor->enterNode($subNode);
                    if (self::DONT_TRAVERSE_CHILDREN === $return) {
                        $traverseChildren = false;
                    } else {
                        if (null !== $return) {
                            $subNode = $return;
                        }
                    }
                }

                if ($traverseChildren) {
                    $subNode = $this->traverseNode($subNode);
                }

                foreach ($this->visitors as $visitor) {
                    if (null !== $return = $visitor->leaveNode($subNode)) {
                        $subNode = $return;
                    }
                }
            }
        }

        return $node;
    }
}
