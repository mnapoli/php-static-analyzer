<?php

namespace PhpAnalyzer\Parser\Node;

use PhpAnalyzer\Scope\Scope;
use PhpAnalyzer\Type\UnknownType;
use PhpParser\Node\Expr\Variable;

/**
 * Variable
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class ReflectedVariable extends Variable implements TypedNode
{
    use BaseNode;

    /**
     * @var Scope
     */
    private $currentScope;

    public function __construct(Variable $node, Scope $currentScope)
    {
        $this->currentScope = $currentScope;

        parent::__construct($node->name, $node->attributes);
    }

    public function getNodeType()
    {
        if (! is_string($this->name)) {
            // TODO no support for dynamic variable name
            return null;
        }

        // TODO refactor?
        $variable = $this->currentScope->getVariable($this->name);

        if (! $variable) {
            // TODO unknown variable?
            return new UnknownType;
        }

        return $variable->getType();
    }
}
