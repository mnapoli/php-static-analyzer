<?php

namespace PhpAnalyzer\Reflection;

/**
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class ReflectionVariable
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string|null
     */
    private $type;

    public function __construct($name, $type = null)
    {
        $this->name = $name;
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string|null
     */
    public function getType()
    {
        return $this->type;
    }
}
