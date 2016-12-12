<?php

namespace PhpAnalyzer\Scope;

use PhpAnalyzer\Log\Logger;
use PhpAnalyzer\Parser\Node\ReflectedVariable;
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
     * @var ReflectedVariable
     */
    private $node;

    /**
     * @var Type[]
     */
    protected $types = [];

    public function __construct(ReflectedVariable $node)
    {
        $this->node = $node;
    }

    public function getName()
    {
        return $this->node->name;
    }

    public function getType()
    {
        if (empty($this->types)) {
            return new UnknownType;
        }

        // TODO handle more than one type detected
        if (count($this->types) > 1) {
            Logger::error('No support for multi-type yet');
        }

        return $this->types[0];
    }

    public function addType(Type $type)
    {
        $this->types[] = $type;
    }
}
