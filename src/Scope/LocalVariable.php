<?php

namespace PhpAnalyzer\Scope;

use PhpAnalyzer\Type\UnknownType;

/**
 * Local variable.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class LocalVariable extends Variable
{
    /**
     * @var \PhpParser\Node\Expr\Variable
     */
    private $node;

    public function __construct(\PhpParser\Node\Expr\Variable $node)
    {
        $this->node = $node;
    }

    public function getName()
    {
        return $this->node->name;
    }

    public function getType()
    {
        return new UnknownType;
    }
}
