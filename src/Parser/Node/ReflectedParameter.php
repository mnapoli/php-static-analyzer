<?php

namespace PhpAnalyzer\Parser\Node;

use PhpAnalyzer\Type\ClassType;
use PhpAnalyzer\Type\UnknownType;
use PhpParser\Node\Param;

/**
 * Parameter
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class ReflectedParameter extends Param implements TypedNode
{
    use BaseNode;

    /**
     * @var ReflectedMethod
     */
    private $method;

    public function __construct(Param $node, ReflectedMethod $method)
    {
        $this->method = $method;

        parent::__construct($node->name, $node->default, $node->type, $node->byRef, $node->variadic, $node->attributes);
    }

    public function getNodeType()
    {
        $typeHint = $this->type;

        if (! $typeHint) {
            return new UnknownType;
        }

        if (is_string($this->type)) {
            // TODO is that a primitive type?
            // TODO handle self and static
            return new UnknownType;
        }

        $name = $this->type->toString();

        try {
            // TODO return earlier if internal type (string, ...)
            $class = $this->method->getScope()->getClass($name);
        } catch (\LogicException $e) {
            return new UnknownType;
        }

        return new ClassType($class);
    }
}
