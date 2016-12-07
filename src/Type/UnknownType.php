<?php

namespace PhpAnalyzer\Type;

/**
 * Unknown type.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class UnknownType implements Type
{
    public function toString() : string
    {
        return '';
    }
}
