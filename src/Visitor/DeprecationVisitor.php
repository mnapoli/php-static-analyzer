<?php

namespace PhpAnalyzer\Visitor;

use PhpAnalyzer\File;

/**
 * Detects deprecated code.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class DeprecationVisitor implements Visitor
{
    public function visit(File $file)
    {
        foreach ($file->getClasses() as $class) {
            $docblock = $class->getDocComment();
            if (! $docblock) {
                continue;
            }

            if (strpos($docblock, '@deprecated') !== false) {
                $class->setDeprecated(true);
            }
        }
    }
}
