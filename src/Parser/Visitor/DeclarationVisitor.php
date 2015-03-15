<?php

namespace PhpAnalyzer\Parser\Visitor;

use PhpAnalyzer\Parser\Context;
use PhpAnalyzer\Reflection\ReflectionClass;
use PhpAnalyzer\Reflection\ReflectionMethod;
use PhpAnalyzer\Reflection\Registry;
use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\NodeVisitorAbstract;

/**
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class DeclarationVisitor extends NodeVisitorAbstract
{
    /**
     * @var Registry
     */
    private $registry;

    /**
     * @var Context
     */
    private $context;

    public function __construct(Registry $registry)
    {
        $this->registry = $registry;
        $this->context = new Context;
    }

    public function enterNode(Node $node)
    {
        switch (true) {
            case $node instanceof Class_:
                $class = new ReflectionClass($node);
                $this->registry->addClass($class);
                $this->context->enterClass($class);
                break;
            case $node instanceof ClassMethod:
                $currentClass = $this->context->getCurrentClass();
                $method = new ReflectionMethod($node, $currentClass);
                $currentClass->addMethod($method);
                $this->context->enterMethod($method);
                echo 'Entering ' . $method->getDeclaringClass()->getName() . '::' . $method->getName() . PHP_EOL;
                break;
        }
    }

    public function leaveNode(Node $node)
    {
        switch (true) {
            case $node instanceof Class_:
                $this->context->leaveClass();
                break;
            case $node instanceof ClassMethod:
                $this->context->leaveMethod();
                break;
        }
    }
}
