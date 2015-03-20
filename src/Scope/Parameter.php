<?php

namespace PhpAnalyzer\Scope;

use PhpAnalyzer\Parser\Node\ReflectedParameter;

/**
 * Method or function parameter.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class Parameter extends Variable
{
    /**
     * @var ReflectedParameter
     */
    private $node;

    public function __construct(ReflectedParameter $node)
    {
        $this->node = $node;
    }

    public function getName()
    {
        return $this->node->name;
    }

    public function getType()
    {
        return $this->node->getNodeType();
    }
}
