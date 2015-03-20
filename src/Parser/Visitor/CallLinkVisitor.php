<?php

namespace PhpAnalyzer\Parser\Visitor;

use PhpAnalyzer\Parser\Node\ReflectedCallableCall;
use PhpParser\NodeVisitorAbstract;
use PhpParser\Node;

/**
 * Link calls to called methods and functions.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class CallLinkVisitor extends NodeVisitorAbstract
{
    public function enterNode(Node $node)
    {
        switch (true) {
            case $node instanceof ReflectedCallableCall:
                $callable = $node->getTargetCallable();
                $callable->addCall($node);
                break;
        }
    }
}
