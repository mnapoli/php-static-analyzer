<?php

namespace PhpAnalyzer\Test\Integration\CallHierarchy;

use PhpAnalyzer\Analyzer;
use PhpAnalyzer\Parser\Node\ReflectedMethodCall;
use PhpAnalyzer\Parser\Node\ReflectedStaticCall;
use PhpAnalyzer\Test\Integration\CallHierarchy\MethodCall\Callee;
use PhpAnalyzer\Test\Integration\CallHierarchy\MethodCall\StaticCallee;

class MethodCallTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function methods_should_know_their_callers()
    {
        $class = $this->analyzeClass(Callee::class);
        $method = $class->getMethod('bar');

        $calls = $method->getCalls();

        $this->assertCount(1, $calls);
        /** @var ReflectedMethodCall $call */
        $call = $calls[0];
        $this->assertInstanceOf(ReflectedMethodCall::class, $call);
        $this->assertEquals(9, $call->getLine());
        $this->assertEquals('Caller.php', $call->getFile()->getRelativeFileName());
        $this->assertSame($method, $call->getTargetCallable());
    }

    /**
     * @test
     */
    public function static_methods_should_know_their_callers()
    {
        $class = $this->analyzeClass(StaticCallee::class);
        $method = $class->getMethod('staticMethod');

        $calls = $method->getCalls();

        $this->assertCount(1, $calls);
        /** @var ReflectedStaticCall $call */
        $call = $calls[0];
        $this->assertInstanceOf(ReflectedStaticCall::class, $call);
        $this->assertEquals(10, $call->getLine());
        $this->assertEquals('Caller.php', $call->getFile()->getRelativeFileName());
        $this->assertSame($method, $call->getTargetCallable());
    }

    private function analyzeClass($class)
    {
        return (new Analyzer)->analyze(__DIR__ . '/MethodCall')->getClass($class);
    }
}