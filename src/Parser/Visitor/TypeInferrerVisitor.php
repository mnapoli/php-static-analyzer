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
use PhpAnalyzer\Type\ClassType;
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
                $node = new ReflectedVariable($node, $this->context->getCurrentScope());
                $this->registerVariableInScope($node);
                return $node;
            case $node instanceof MethodCall:
                return new ReflectedMethodCall($node, $this->context->getCurrentScope());
            case $node instanceof StaticCall:
                return new ReflectedStaticCall($node, $this->context->getCurrentScope());
            case $node instanceof Node\Expr\Assign;
                $this->processAssignment($node);
                break;
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

    private function processAssignment(Node\Expr\Assign $node)
    {
        if (! $node->var instanceof Variable) {
            return;
        }

        $expression = $node->expr;

        // $foo = new Class()
        if ($expression instanceof Node\Expr\New_) {
            if (! is_string($node->var->name)) {
                // Dynamic variable name
                return;
            }

            $variableName = (string) $node->var->name;

            $scope = $this->context->getCurrentScope();
            $variable = $scope->getVariable($variableName);

            if (! $variable) {
                return;
            }

            if (! $expression->class instanceof Node\Name) {
                // Dynamic class name
                return;
            }

            $class = $scope->getClass($expression->class->toString());

            $variable->addType(new ClassType($class));
        }
    }
}
