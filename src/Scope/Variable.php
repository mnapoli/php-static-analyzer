<?php

namespace PhpAnalyzer\Scope;

use PhpAnalyzer\Type\Type;

/**
 * Variable
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
abstract class Variable
{
    /**
     * @return string
     */
    abstract public function getName();

    /**
     * @return Type
     */
    abstract public function getType();

    abstract public function addType(Type $type);
}
