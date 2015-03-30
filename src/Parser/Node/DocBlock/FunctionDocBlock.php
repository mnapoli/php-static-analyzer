<?php

namespace PhpAnalyzer\Parser\Node\DocBlock;

use PhpAnalyzer\Scope\Scope;
use PhpAnalyzer\Type\ClassType;
use PhpAnalyzer\Type\PrimitiveType;
use PhpAnalyzer\Type\Type;
use PhpAnalyzer\Type\UnknownType;
use phpDocumentor\Reflection\DocBlock;

/**
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class FunctionDocBlock
{
    /**
     * @var DocBlock
     */
    private $parser;

    /**
     * @var Scope
     */
    private $scope;

    public function __construct(DocBlock $parser, Scope $scope)
    {
        $this->parser = $parser;
        $this->scope = $scope;
    }

    /**
     * @return Type
     */
    public function getReturnType()
    {
        if (! $this->parser->hasTag('return')) {
            return new UnknownType;
        }

        $tag = $this->parser->getTagsByName('return');
        $tag = reset($tag);

        $typeName = $tag->getContent();

        // Internal types
        if (PrimitiveType::isPrimitiveType($typeName)) {
            return PrimitiveType::get($typeName);
        }

        try {
            // TODO internal types (string, ...)
            $class = $this->scope->getClass($typeName);
        } catch (\LogicException $e) {
            return new UnknownType;
        }

        // TODO
        return new ClassType($class);
    }
}
