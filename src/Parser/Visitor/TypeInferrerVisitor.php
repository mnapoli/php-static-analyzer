<?php

namespace PhpAnalyzer\Parser\Visitor;

use PhpAnalyzer\File;
use PhpAnalyzer\Parser\Context;
use PhpAnalyzer\Parser\Node\ReflectedClass;
use PhpAnalyzer\Parser\Node\ReflectedInterface;
use PhpAnalyzer\Parser\Node\ReflectedMethod;
use PhpAnalyzer\Parser\Node\ReflectedMethodCall;
use PhpAnalyzer\Parser\Node\ReflectedStaticCall;
use PhpAnalyzer\Parser\Node\ReflectedVariable;
use PhpAnalyzer\Scope\LocalVariable;
use PhpAnalyzer\Scope\Parameter;
use PhpAnalyzer\Visitor\ProjectVisitor;
use PhpParser\Node\Expr\StaticCall;
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
class TypeInferrerVisitor extends NodeVisitorAbstract implements ProjectVisitor
{
    /**
     * @var Context
     */
    private $context;

    public function setFile(File $file)
    {
    }

    public function setContext(Context $context)
    {
        $this->context = $context;
    }

    public function enterNode(Node $node)
    {
        switch (true) {
            case $node instanceof ReflectedClass:
                $this->context->enterClass($node);
                break;
            case $node instanceof ReflectedInterface:
                $this->context->enterClass($node);
                break;
            case $node instanceof ReflectedMethod:
                $this->context->enterMethod($node);
                $this->processMethod($node);
                break;
        }

        return null;
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
            case $node instanceof Variable:
                if (!$this->context->getCurrentMethod()) {
                    // TODO variables declared outside of methods
                    return null;
                }
                $node = new ReflectedVariable($node, $this->context->getCurrentScope());
                $this->registerVariableInScope($node);
                return $node;
            case $node instanceof MethodCall:
                return new ReflectedMethodCall($node, $this->context->getCurrentScope());
            case $node instanceof StaticCall:
                return new ReflectedStaticCall($node, $this->context->getCurrentScope());
        }

        return null;
    }

    private function processMethod(ReflectedMethod $node)
    {
        $scope = $node->getScope();

        $scope->addVariable(new This($node->getDeclaringClass()));

        foreach ($node->getParameters() as $parameter) {
            $scope->addVariable(new Parameter($parameter));
        }
    }

    private function registerVariableInScope(ReflectedVariable $node)
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
