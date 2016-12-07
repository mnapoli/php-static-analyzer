<?php

namespace PhpAnalyzer\Type;

/**
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class ObjectType implements Type
{
    /**
     * @var string
     */
    private $class;

    public function __construct(string $class)
    {
        $this->class = $class;
    }

    public function toString() : string
    {
        return $this->class;
    }
}
