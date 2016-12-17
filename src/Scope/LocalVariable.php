<?php

namespace PhpAnalyzer\Scope;

use PhpAnalyzer\Type\Type;
use PhpAnalyzer\Type\UnknownType;

/**
 * Local variable.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class LocalVariable extends Variable
{
    /**
     * @var \PhpAnalyzer\Node\Operation\Variable
     */
    private $node;

    /**
     * @var Type[]
     */
    protected $types = [];

    public function __construct(\PhpAnalyzer\Node\Operation\Variable $node)
    {
        $this->node = $node;
    }

    public function getName()
    {
        return $this->node->getName();
    }

    public function getType()
    {
        if (empty($this->types)) {
            return new UnknownType;
        }

        // TODO handle more than one type detected
        if (count($this->types) > 1) {
            throw new \Exception('No support for multi-types yet');
        }

        return $this->types[0];
    }

    public function addType(Type $type)
    {
        $this->types[] = $type;
    }
}
