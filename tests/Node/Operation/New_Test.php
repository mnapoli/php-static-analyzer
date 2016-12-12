<?php
declare(strict_types = 1);

namespace PhpAnalyzer\Test\Node\Operation;

use PhpAnalyzer\Node\Operation\New_;

class New_Test extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function guesses type from class name()
    {
        $node = new New_('Foo', []);
        self::assertEquals('Foo', $node->getReturnType()->toString());
    }
}
