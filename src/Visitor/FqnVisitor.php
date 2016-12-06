<?php
declare(strict_types = 1);

namespace PhpAnalyzer\Visitor;

use PhpAnalyzer\File;

/**
 * Resolves fully qualified names of:
 *
 * - classes
 * - functions
 * - constants
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class FqnVisitor
{
    public function visit(File $file)
    {
        $namespace = $file->getNamespace();

        foreach ($file->getClasses() as $class) {
            $class->setNamespace($namespace);
        }
    }
}
