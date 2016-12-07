<?php
declare(strict_types = 1);

namespace PhpAnalyzer\Visitor;

use PhpAnalyzer\File;

/**
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
interface Visitor
{
    public function visit(File $file);
}
