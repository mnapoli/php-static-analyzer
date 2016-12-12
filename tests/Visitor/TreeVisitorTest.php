<?php
declare(strict_types = 1);

namespace PhpAnalyzer\Test\Visitor;

use PhpAnalyzer\Node\Declaration\ClassMethod;
use PhpAnalyzer\Project;
use PhpAnalyzer\Visitor\Traversable;
use PhpAnalyzer\Visitor\TreeVisitor;

class TreeVisitorTest extends \PHPUnit_Framework_TestCase
{
    public function test visit nodes()
    {
        $project = new Project([__DIR__ . '/TreeVisitor']);

        $visitor = new class extends TreeVisitor {
            public $classCount = 0;
            protected function visitNode(Traversable $node)
            {
                TreeVisitorTest::assertInstanceOf(ClassMethod::class, $node);
                $this->classCount++;
            }
            protected function getTargetNodes() : array
            {
                return [ClassMethod::class];
            }
        };
        $visitor->visit($project);

        self::assertEquals(2, $visitor->classCount);
    }
}
