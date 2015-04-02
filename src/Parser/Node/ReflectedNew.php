<?php

namespace PhpAnalyzer\Parser\Node;

use PhpAnalyzer\Log\Logger;
use PhpAnalyzer\Scope\Scope;
use PhpAnalyzer\Type\ClassType;
use PhpAnalyzer\Type\UnknownType;
use PhpParser\Node\Expr\New_;
use PhpParser\Node\Name;

/**
 * Method call.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class ReflectedNew extends New_ implements TypedNode
{
    use BaseNode;

    /**
     * @var Scope
     */
    private $currentScope;

    public function __construct(New_ $node, Scope $currentScope)
    {
        $this->currentScope = $currentScope;

        parent::__construct($node->class, $node->args, $node->attributes);
    }

    public function getNodeType()
    {
        if (! $this->class instanceof Name) {
            // Dynamic class name
            return new UnknownType;
        }

        try {
            $class = $this->currentScope->getClass($this->class->toString());
        } catch (\LogicException $e) {
            Logger::warning('Creation with "new" of an unknown class {class}', ['class' => $this->class->toString()]);
            return new UnknownType;
        }

        return new ClassType($class);
    }
}
