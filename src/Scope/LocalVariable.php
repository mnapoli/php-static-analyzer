<?php

namespace PhpAnalyzer\Scope;

use PhpAnalyzer\Parser\Node\ReflectedVariable;
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
        // TODO guess with assignments?
        // e.g. $foo = new Foo();
        return new UnknownType;
    }
}
