<?php

namespace PhpAnalyzer\Parser\Node;

use PhpAnalyzer\Scope\Scope;
use PhpParser\Node\Stmt\Interface_;

/**
 * Interface
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class ReflectedInterface extends Interface_ implements ReflectedType
{
    use SubNodeTraversing;

    /**
     * @var string
     */
    private $fqn;

    /**
     * @var Scope
     */
    private $scope;

    public function __construct(Interface_ $node, Scope $scope)
    {
        parent::__construct($node->name, $this->getSubNodes($node), $node->getAttributes());

        $this->fqn = $node->namespacedName->toString();
        $this->scope = $scope;
    }

    /**
     * @return string
     */
    public function getFQN()
    {
        return $this->fqn;
    }

    public function getMethods()
    {
        // TODO merge with parents
        return parent::getMethods();
    }

    public function hasMethod($name)
    {
        foreach ($this->getMethods() as $method) {
            if ($method->name === $name) {
                return true;
            }
        }
        return false;
    }

    public function getMethod($name)
    {
        foreach ($this->getMethods() as $method) {
            if ($method->name === $name) {
                return $method;
            }
        }
        throw new \LogicException(sprintf('Method %s::%s() not found', $this->getFQN(), $name));
    }

    public function getScope()
    {
        return $this->scope;
    }
}
