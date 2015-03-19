<?php

namespace PhpAnalyzer\Parser\Node;

use PhpAnalyzer\Scope\Scope;
use PhpParser\Node\Stmt\Class_;

/**
 * Class
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class ReflectedClass extends Class_ implements ReflectedType
{
    /**
     * @var Scope
     */
    private $scope;

    /**
     * @var string
     */
    private $fqn;

    public function __construct(Class_ $node, Scope $scope)
    {
        $this->scope = new Scope($scope);

        parent::__construct($node->name, $node->subNodes, $node->getAttributes());

        $this->fqn = $node->namespacedName->toString();
    }

    /**
     * @return string
     */
    public function getFQN()
    {
        return $this->fqn;
    }

    /**
     * @return ReflectedProperty[]
     */
    public function getProperties()
    {
        // TODO merge with parent & traits
        return array_filter($this->stmts, function ($stmt) {
            return $stmt instanceof ReflectedProperty;
        });
    }

    /**
     * @param string $name
     * @return null|ReflectedProperty
     */
    public function getProperty($name)
    {
        $properties = $this->getProperties();

        foreach ($properties as $property) {
            if ($property->name === $name) {
                return $property;
            }
        }

        return null;
    }

    /**
     * @return ReflectedMethod[]
     */
    public function getMethods()
    {
        // TODO merge with parent & traits
        return parent::getMethods();
    }

    /**
     * @param string $name
     * @return bool
     */
    public function hasMethod($name)
    {
        foreach ($this->getMethods() as $method) {
            if ($method->name === $name) {
                return true;
            }
        }
        return false;
    }

    /**
     * @param string $name
     * @return null|ReflectedMethod
     */
    public function getMethod($name)
    {
        foreach ($this->getMethods() as $method) {
            if ($method->name === $name) {
                return $method;
            }
        }
        throw new \LogicException(sprintf('Method %s::%s() not found', $this->getFQN(), $name));
    }

    /**
     * @return Scope
     */
    public function getScope()
    {
        return $this->scope;
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
