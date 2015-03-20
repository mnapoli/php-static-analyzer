<?php

namespace PhpAnalyzer\Parser\Visitor;

use PhpAnalyzer\Parser\Context;
use PhpAnalyzer\Parser\Node\ReflectedClass;
use PhpAnalyzer\Parser\Node\ReflectedInterface;
use PhpAnalyzer\Parser\Node\ReflectedMethod;
use PhpAnalyzer\Parser\Node\ReflectedMethodCall;
use PhpAnalyzer\Scope\LocalVariable;
use PhpAnalyzer\Scope\Parameter;
use PhpParser\NodeVisitorAbstract;
use PhpAnalyzer\Scope\This;
use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\Variable;

/**
 * Infer types of nodes.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class TypeInferrerVisitor extends NodeVisitorAbstract
{
    /**
     * @var Context
     */
    private $context;

    public function __construct(Context $context)
    {
        $this->context = $context;
    }

    public function enterNode(Node $node)
    {
        switch (true) {
            case $node instanceof ReflectedClass:
                $this->context->enterClass($node);
                $this->processClass($node);
                break;
            case $node instanceof ReflectedInterface:
                $this->context->enterClass($node);
                break;
            case $node instanceof ReflectedMethod:
                $this->context->enterMethod($node);
                $this->processMethod($node);
                break;
            case $node instanceof Variable:
                $this->processVariable($node);
                break;
            case $node instanceof MethodCall:
                return new ReflectedMethodCall($node, $this->context->getCurrentScope());
                break;
        }
    }

    public function leaveNode(Node $node)
    {
        switch (true) {
            case $node instanceof ReflectedClass:
                $this->context->leaveClass();
                break;
            case $node instanceof ReflectedMethod:
                $this->context->leaveMethod();
                break;
        }
    }

    private function processClass(ReflectedClass $node)
    {
        $node->getScope()->addVariable(new This($node));
    }

    private function processMethod(ReflectedMethod $node)
    {
        $scope = $node->getScope();
        foreach ($node->getParameters() as $parameter) {
            $scope->addVariable(new Parameter($parameter));
        }
    }

    private function processVariable(Variable $node)
    {
        // Variable name is dynamic
        if (! is_string($node->name)) {
            return;
        }

        $currentScope = $this->context->getCurrentScope();

        if ($currentScope->hasVariable($node->name)) {
            return;
        }

        $currentScope->addVariable(new LocalVariable($node));
    }
}
