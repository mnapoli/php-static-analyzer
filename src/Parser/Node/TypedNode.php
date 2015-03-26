<?php

namespace PhpAnalyzer\Parser\Node;

use PhpAnalyzer\Type\Type;

/**
 * Node that has a type (class, interface, internal PHP type, ...).
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
interface TypedNode extends ReflectedNode
{
    /**
     * @return Type
     */
    public function getNodeType();
}
