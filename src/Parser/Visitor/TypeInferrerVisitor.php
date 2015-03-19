<?php

namespace PhpAnalyzer\Parser\Visitor;

use PhpAnalyzer\Closure\CachedClosure;
use PhpAnalyzer\Parser\Context;
use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\Variable;

/**
 * Infer types of nodes.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class TypeInferrerVisitor
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
            case $node instanceof Variable:
                $this->processVariable($node);
                break;
            case $node instanceof MethodCall:
                $this->processMethodCall($node);
                break;
        }
    }

    public function processVariable(Variable $node)
    {
        // Variable name is dynamic
        if (! is_string($node->name)) {
            return;
        }

        $currentScope = $this->context->getCurrentScope();

        $node->typeResolver = new CachedClosure(function () use ($node, $currentScope) {
            $variable = $currentScope->getVariable($node->name);

            if ($variable) {
                return $variable->getType();
            }
            return null;
        });
    }

    public function processMethodCall(MethodCall $node)
    {
        // Method name is dynamic
        if (! is_string($node->name)) {
            return;
        }

        $node->typeResolver = new CachedClosure(function () use ($node) {
            $varTypeResolver = $node->var->typeResolver;
            $varType = $varTypeResolver();

            // Unknown type
            if (!$varType) {
                return null;
            }

            var_dump($varType);
            die();
        });
    }
}
