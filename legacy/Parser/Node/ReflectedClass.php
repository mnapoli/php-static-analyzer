<?php

namespace PhpAnalyzer\Parser\Node;

use PhpAnalyzer\Log\Logger;
use PhpAnalyzer\Scope\Scope;
use PhpParser\Node\Stmt\Class_;

/**
 * Class
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class ReflectedClass extends Class_ implements ReflectedType
{
    use BaseNode;

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
        $this->scope = $scope;
        $this->fqn = $node->namespacedName->toString();

        parent::__construct($node->name, $this->getSubNodes($node), $node->getAttributes());
    }

    /**
     * {@inheritdoc}
     */
    public function getFQN()
    {
        return $this->fqn;
    }

    /**
     * @return ReflectedClass|null
     */
    public function getParentClass()
    {
        if (! $this->extends) {
            return null;
        }

        try {
            return $this->scope->getClass($this->extends->toString());
        } catch (\LogicException $e) {
            Logger::warning('Unknown parent class {class}', ['class' => $this->extends->toString()]);
            return null;
        }
    }

    /**
     * @return ReflectedInterface[]
     */
    public function getImplementedInterfaces()
    {
        $interfaces = [];
        foreach ($this->implements as $interfaceName) {
            try {
                $interfaces[] = $this->scope->getClass($interfaceName->toString());
            } catch (\LogicException $e) {
                Logger::warning('Unknown implemented interface {interface}', [
                    'interface' => $interfaceName->toString()
                ]);
            }
        }
        return $interfaces;
    }

    /**
     * {@inheritdoc}
     */
    public function getProperties($visibility = null)
    {
        // TODO merge with traits
        $properties = [];
        foreach ($this->stmts as $stmt) {
            if (! $stmt instanceof ReflectedProperty) {
                continue;
            }
            if ($visibility === null || ($visibility & $stmt->getVisibility())) {
                $properties[$stmt->getName()] = $stmt;
            }
        }

        $parentClass = $this->getParentClass();
        if (! $parentClass) {
            return $properties;
        }

        return array_merge(
            $parentClass->getProperties(self::MODIFIER_PROTECTED | self::MODIFIER_PUBLIC),
            $properties
        );
    }

    /**
     * {@inheritdoc}
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
     * {@inheritdoc}
     */
    public function getMethods($visibility = null)
    {
        // TODO merge with traits and parent interfaces
        $methods = [];
        foreach ($this->stmts as $stmt) {
            if (! $stmt instanceof ReflectedMethod) {
                continue;
            }
            if ($visibility === null || ($visibility & $stmt->getVisibility())) {
                $methods[$stmt->getName()] = $stmt;
            }
        }

        return array_merge($this->getParentMethods(), $methods);
    }

    /**
     * {@inheritdoc}
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
     * {@inheritdoc}
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
     * Returns the methods defined in parent classes and implemented interfaces.
     *
     * @return ReflectedMethod[]
     */
    private function getParentMethods()
    {
        $parentMethods = [];

        $interfaces = $this->getImplementedInterfaces();
        foreach ($interfaces as $interface) {
            $parentMethods = array_merge($parentMethods, $interface->getMethods());
        }

        $parentClass = $this->getParentClass();
        if ($parentClass) {
            $parentMethods = array_merge(
                $parentMethods,
                $parentClass->getMethods(self::MODIFIER_PROTECTED | self::MODIFIER_PUBLIC)
            );
        }

        return $parentMethods;
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
