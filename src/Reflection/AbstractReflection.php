<?php

namespace PhpAnalyzer\Reflection;

use PhpParser\Node;

/**
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class AbstractReflection
{
    /**
     * @var Node
     */
    protected $node;

    public function __construct(Node $node)
    {
        $this->node = $node;
    }

    /**
     * @return Node
     */
    public function getNode()
    {
        return $this->node;
    }
}
