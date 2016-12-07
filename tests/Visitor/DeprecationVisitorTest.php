<?php
declare(strict_types = 1);

namespace PhpAnalyzer\Test\Visitor;

use PhpAnalyzer\File;
use PhpAnalyzer\Visitor\DeprecationVisitor;
use PHPUnit\Framework\TestCase;

/**
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class DeprecationVisitorTest extends TestCase
{
    public function test resolve class fqn()
    {
        $file = new File(__DIR__ . '/Deprecation/classes.php');
        (new DeprecationVisitor)->visit($file);

        self::assertFalse($file->getClass('Foo')->isDeprecated());
        self::assertTrue($file->getClass('Bar')->isDeprecated());
        self::assertFalse($file->getClass('Baz')->isDeprecated());
    }
}
