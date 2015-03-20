<?php

namespace PhpAnalyzer\Parser\Visitor;

use PhpAnalyzer\Parser\Context;
use PhpAnalyzer\Parser\Node\ReflectedClass;
use PhpAnalyzer\Parser\Node\ReflectedInterface;
use PhpAnalyzer\Parser\Node\ReflectedMethod;
use PhpAnalyzer\Parser\Node\ReflectedParameter;
use PhpAnalyzer\Parser\Node\ReflectedProperty;
use PhpAnalyzer\Parser\Node\ReflectedType;
use PhpAnalyzer\Scope\Scope;
use PhpParser\Node;
use PhpParser\Node\Param;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Interface_;
use PhpParser\Node\Stmt\Property;
use PhpParser\NodeVisitorAbstract;

/**
 * Turns nodes into reflection objects.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class ReflectionVisitor extends NodeVisitorAbstract
{
    /**
     * @var Scope
     */
    private $rootScope;

    /**
     * @var Context
     */
    private $context;

    public function __construct(Scope $rootScope, Context $context)
    {
        $this->rootScope = $rootScope;
        $this->context = $context;
    }

    public function enterNode(Node $node)
    {
        switch (true) {
            case $node instanceof Class_:
                $newNode = new ReflectedClass($node, $this->rootScope->enterSubScope());
                $this->rootScope->addClass($newNode);
                $this->context->enterClass($newNode);
                return $newNode;
            case $node instanceof Interface_:
                $newNode = new ReflectedInterface($node, $this->rootScope->enterSubScope());
                $this->rootScope->addClass($newNode);
                $this->context->enterClass($newNode);
                return $newNode;
            case $node instanceof ClassMethod:
                $class = $this->context->getCurrentClass();
                $newNode = new ReflectedMethod($node, $class, $class->getScope()->enterSubScope());
                $this->context->enterMethod($newNode);
                return $newNode;
        }

        return null;
    }

    public function leaveNode(Node $node)
    {
        switch (true) {
            case $node instanceof ReflectedType:
                $this->context->leaveClass();
                break;
            case $node instanceof ReflectedMethod:
                $this->context->leaveMethod();
                break;
            case $node instanceof Property:
                // TODO for now doesn't support properties declared as list
                return new ReflectedProperty($node, $node->props[0], $this->context->getCurrentClass());
            case $node instanceof Param:
                return new ReflectedParameter($node, $this->context->getCurrentMethod());
        }

        return null;
    }
}
