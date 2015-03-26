<?php

namespace PhpAnalyzer\Visitor;

use PhpAnalyzer\File;
use PhpAnalyzer\Parser\Context;

/**
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
interface ProjectVisitor
{
    public function setFile(File $file);

    public function setContext(Context $context);
}
