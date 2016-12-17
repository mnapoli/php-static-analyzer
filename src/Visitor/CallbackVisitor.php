<?php
declare(strict_types = 1);

namespace PhpAnalyzer\Visitor;

/**
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class CallbackVisitor extends Visitor
{
    /**
     * @var callable
     */
    private $callback;

    /**
     * @var string[]
     */
    private $targetNode;

    /**
     * @param callable $callback Callback to execute on each node. The target node will be guessed from the
     *                           callback's parameter type-hint.
     */
    public function __construct(callable $callback)
    {
        $this->callback = $callback;

        // Extract the target node from the callback type-hint
        $reflection = new \ReflectionFunction($callback);
        assert($reflection->getNumberOfParameters() === 1);
        $parameter = $reflection->getParameters()[0];

        $this->targetNode = $parameter->getType()->__toString();
    }

    protected function visitNode(Traversable $node)
    {
        $callback = $this->callback;
        $callback($node);
    }

    protected function getTargetNodes() : array
    {
        return [$this->targetNode];
    }
}
