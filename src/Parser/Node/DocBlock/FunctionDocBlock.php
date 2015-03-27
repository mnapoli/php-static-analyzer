<?php

namespace PhpAnalyzer\Parser\Node\DocBlock;

use PhpAnalyzer\Scope\Scope;
use PhpAnalyzer\Type\ClassType;
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

        try {
            // TODO internal types (string, ...)
            var_dump($tag->getContent());
            $class = $this->scope->getClass($tag->getContent());
        } catch (\LogicException $e) {
            return new UnknownType;
        }

        // TODO
        return new ClassType($class);
    }
}
