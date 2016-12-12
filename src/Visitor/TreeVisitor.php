<?php
declare(strict_types = 1);

namespace PhpAnalyzer\Visitor;

/**
 * Visit a whole AST.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
abstract class TreeVisitor
{
    public function visit(Traversable $node)
    {
        foreach ($this->getTargetNodes() as $class) {
            if ($node instanceof $class) {
                $this->visitNode($node);
            }
        }

        foreach ($node->getChildren() as $child) {
            if (!$child instanceof Traversable) {
                throw new \Exception(sprintf(
                    '%s::getChildren() returned a %s which is not an instance of Traversable',
                    is_object($node) ? get_class($node) : gettype($node),
                    is_object($child) ? get_class($child) : gettype($child)
                ));
            }
            $this->visit($child);
        }
    }

    abstract protected function visitNode(Traversable $node);

    /**
     * @return string[] Class names of the nodes to visit.
     */
    abstract protected function getTargetNodes() : array;
}
