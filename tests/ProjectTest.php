<?php
declare(strict_types = 1);

namespace PhpAnalyzer\Test;

use PhpAnalyzer\Project;
use PhpAnalyzer\Visitor\FqnVisitor;

class ProjectTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function analyzes classes()
    {
        $project = new Project([__DIR__ . '/Project']);

        $classes = $project->getClasses();
        self::assertCount(2, $classes);
        self::assertEquals('Foo', $classes[0]->getName());
        self::assertEquals('Bar', $classes[1]->getName());
    }

    /**
     * @test
     */
    public function applies visitors()
    {
        $project = new Project([__DIR__ . '/Project'], [new FqnVisitor]);

        $classes = $project->getClasses();
        self::assertCount(2, $classes);
        self::assertEquals('Project\Dir1\Foo', $classes[0]->getName());
        self::assertEquals('Project\Dir2\Bar', $classes[1]->getName());
    }
}
