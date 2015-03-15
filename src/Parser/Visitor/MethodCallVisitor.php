<?php

namespace PhpAnalyzer\Parser\Visitor;

use PhpAnalyzer\Parser\Context;
use PhpAnalyzer\Reflection\Registry;
use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\NodeVisitorAbstract;

/**
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class MethodCallVisitor extends NodeVisitorAbstract
{
    /**
     * @var Registry
     */
    private $registry;

    /**
     * @var Context
     */
    private $context;

    public function __construct(Registry $registry, Context $context)
    {
        $this->registry = $registry;
        $this->context = $context;
    }

    public function enterNode(Node $node)
    {
        if (! $node instanceof MethodCall) {
            return;
        }

        $currentMethod = $this->context->getCurrentMethod();

        if ($currentMethod === null) {
            // TODO log warning
            return;
        }

        $variableName = $node->var->name;
        $methodName = $node->name;

        // Variable name is dynamic
        if (! is_string($variableName)) {
            return;
        }
        // Method name is dynamic
        if (! is_string($methodName)) {
            return;
        }
    }
}
