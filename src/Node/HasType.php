<?php
declare(strict_types = 1);

namespace PhpAnalyzer\Node;

use PhpAnalyzer\Type\Type;

/**
 * A node that has a type (e.g. a variable, a property, an expression, a method call, ...)
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
interface HasType
{
    public function getReturnType() : Type;
}
