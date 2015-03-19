<?php

namespace PhpAnalyzer\Parser\Visitor;

use PhpAnalyzer\Parser\Context;
use PhpAnalyzer\Parser\Node\ReflectedClass;
use PhpAnalyzer\Parser\Node\ReflectedInterface;
use PhpAnalyzer\Parser\Node\ReflectedMethod;
use PhpAnalyzer\Parser\Node\ReflectedProperty;
use PhpAnalyzer\Scope\Scope;
use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
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
            case $node instanceof Node\Stmt\Interface_:
                $newNode = new ReflectedInterface($node, $this->rootScope->enterSubScope());
                $this->rootScope->addClass($newNode);
                $this->context->enterClass($newNode);
                return $newNode;
            case $node instanceof Property:
                // TODO for now doesn't support properties declared as list
                $newNode = new ReflectedProperty($node, $node->props[0], $this->context->getCurrentClass());
                return $newNode;
            case $node instanceof ClassMethod:
                $class = $this->context->getCurrentClass();
                $newNode = new ReflectedMethod($node, $class, $class->getScope()->enterSubScope());
                $this->context->enterMethod($newNode);
                return $newNode;
        }
    }

    public function leaveNode(Node $node)
    {
        switch (true) {
            case $node instanceof Class_:
                $class = $this->context->getCurrentClass();
                // Update sub nodes
                $class->stmts = $node->stmts;
                $this->context->leaveClass();
                break;
            case $node instanceof ClassMethod:
                $this->context->leaveMethod();
                break;
        }
    }
}
