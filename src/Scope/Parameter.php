<?php

namespace PhpAnalyzer\Scope;

use PhpParser\Node\Param;

/**
 * Method or function parameter.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class Parameter extends Variable
{
    /**
     * @var Param
     */
    private $node;

    public function __construct(Param $node)
    {
        $this->node = $node;
    }

    public function getName()
    {
        return $this->node->name;
    }
}
