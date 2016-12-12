<?php
declare(strict_types = 1);

namespace PhpAnalyzer\Visitor;

/**
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
interface Traversable
{
    /**
     * @return Traversable[]
     */
    public function getChildren() : array;
}
