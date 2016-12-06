<?php
declare(strict_types = 1);

namespace PhpAnalyzer\Test\Visitor;

use PhpAnalyzer\File;
use PhpAnalyzer\Visitor\FqnVisitor;
use PHPUnit\Framework\TestCase;

/**
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class FqnVisitorTest extends TestCase
{
    public function test resolve class fqn()
    {
        $file = new File(__DIR__ . '/Fqn/class.php');

        // Before
        self::assertEquals('Bar', $file->getClass('Bar')->getName());

        (new FqnVisitor)->visit($file);

        // After
        self::assertEquals('Foo\Bar', $file->getClass('Foo\Bar')->getName());
    }

    public function test resolve class in global namespace()
    {
        $file = new File(__DIR__ . '/Fqn/class-global-namespace.php');
        (new FqnVisitor)->visit($file);
        self::assertEquals('Foo', $file->getClass('Foo')->getName());
    }
}
