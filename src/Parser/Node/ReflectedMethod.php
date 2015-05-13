<?php

namespace PhpAnalyzer\Parser\Node;

use PhpAnalyzer\Log\Logger;
use PhpAnalyzer\Parser\Node\DocBlock\FunctionDocBlock;
use PhpAnalyzer\Scope\Scope;
use PhpAnalyzer\Type\Type;
use phpDocumentor\Reflection\DocBlock;
use PhpParser\Node\Stmt\ClassMethod;

/**
 * Method
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class ReflectedMethod extends ClassMethod implements ReflectedCallable
{
    use BaseNode;

    /**
     * @var ReflectedType
     */
    protected $class;

    /**
     * @var Scope
     */
    private $scope;

    /**
     * @var ReflectedCallableCall
     */
    private $calls = [];

    /**
     * @var FunctionDocBlock
     */
    private $docBlock;

    public function __construct(ClassMethod $node, ReflectedType $class, Scope $scope)
    {
        $this->class = $class;
        $this->scope = $scope;

        parent::__construct($node->name, $this->getSubNodes($node), $node->attributes);
    }

    /**
     * @return ReflectedType
     */
    public function getDeclaringClass()
    {
        return $this->class;
    }

    /**
     * @return Type
     */
    public function getReturnType()
    {
        return $this->getDocBlock()->getReturnType();
    }

    /**
     * @return Scope
     */
    public function getScope()
    {
        return $this->scope;
    }

    /**
     * @return ReflectedParameter[]
     */
    public function getParameters()
    {
        return $this->params;
    }

    public function addCall(ReflectedCallableCall $call)
    {
        if (! $call instanceof ReflectedCallableCall) {
            Logger::error('Unexpected situation');
            return;
        }

        $this->calls[] = $call;
    }

    /**
     * @return ReflectedCallableCall[]
     */
    public function getCalls()
    {
        return $this->calls;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return bool
     */
    public function isConstructor()
    {
        return $this->name === '__construct';
    }

    /**
     * @return bool
     */
    public function isDestructor()
    {
        return $this->name === '__destruct';
    }

    /**
     * @return int
     */
    public function getVisibility()
    {
        return $this->type;
    }

    /**
     * @return FunctionDocBlock
     */
    protected function getDocBlock()
    {
        if (! $this->docBlock) {
            $docComment = $this->getDocComment();
            $docCommentString = $docComment ? $docComment->getText() : '';

            try {
                $this->docBlock = new FunctionDocBlock(new DocBlock($docCommentString), $this->getScope());
            } catch (\Exception $e) {
                Logger::error('Invalid method docblock: ' . $e->getMessage());
                $this->docBlock = new FunctionDocBlock(new DocBlock(''), $this->getScope());
            }
        }

        return $this->docBlock;
    }
}
