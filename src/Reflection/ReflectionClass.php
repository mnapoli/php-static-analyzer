<?php

namespace PhpAnalyzer\Reflection;

use PhpParser\Node\Stmt\Class_;

/**
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class ReflectionClass extends AbstractReflection
{
    /**
     * @var Class_
     */
    protected $node;

    /**
     * @var ReflectionMethod[]
     */
    private $methods = [];

    public function __construct(Class_ $node)
    {
        parent::__construct($node);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->node->namespacedName->toString();
    }

    public function addMethod(ReflectionMethod $method)
    {
        $this->methods[$method->getName()] = $method;
    }

    /**
     * @return ReflectionMethod[]
     */
    public function getMethods()
    {
        return $this->methods;
    }

    /**
     * @return ReflectionMethod
     */
    public function getMethod($name)
    {
        return $this->methods[$name];
    }

    // Methods from the PHP reflection
//    public function getConstant ( string $name );
//    public function getConstants ();
//    public function getConstructor ();
//    public function getDefaultProperties ();
//    public function getDocComment ();
//    public function getEndLine ();
//    public function getExtension ();
//    public function getExtensionName ();
//    public function getFileName ();
//    public function getInterfaceNames ();
//    public function getInterfaces ();
//    public function getModifiers ();
//    public function getNamespaceName ();
//    public function getParentClass ();
//    public function getProperties ([ int $filter ] );
//    public function getProperty ( string $name );
//    public function getShortName ();
//    public function getStartLine ();
//    public function getStaticProperties ();
//    public function getStaticPropertyValue ( string $name [, mixed &$def_value ] );
//    public function getTraitAliases ();
//    public function getTraitNames ();
//    public function getTraits ();
//    public function hasConstant ( string $name );
//    public function hasMethod ( string $name );
//    public function hasProperty ( string $name );
//    public function implementsInterface ( string $interface );
//    public function inNamespace ();
//    public function isAbstract ();
//    public function isCloneable ();
//    public function isFinal ();
//    public function isInstance ( object $object );
//    public function isInstantiable ();
//    public function isInterface ();
//    public function isInternal ();
//    public function isIterateable ();
//    public function isSubclassOf ( string $class );
//    public function isTrait ();
//    public function isUserDefined ();
}
