<?php

namespace PhpAnalyzer\Node;

use PhpAnalyzer\Node\Declaration\Class_;
use PhpAnalyzer\Node\Declaration\Namespace_;
use PhpAnalyzer\Node\Operation\Assign;
use PhpAnalyzer\Node\Operation\ConstantUsage;
use PhpAnalyzer\Node\Operation\Echo_;
use PhpAnalyzer\Node\Operation\MethodCall;
use PhpAnalyzer\Node\Operation\New_;
use PhpAnalyzer\Node\Operation\Operation;
use PhpAnalyzer\Node\Operation\PrimitiveValue;
use PhpAnalyzer\Node\Operation\Print_;
use PhpAnalyzer\Node\Operation\Return_;
use PhpAnalyzer\Node\Operation\Throw_;
use PhpAnalyzer\Node\Operation\Variable;
use PhpAnalyzer\Scope\Scope;
use PhpAnalyzer\Visitor\Traversable;

/**
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
abstract class Node implements Traversable
{
    const AST_TO_NODES = [
        \ast\AST_STMT_LIST => NodeList::class,
        \ast\AST_CLASS => Class_::class,
        \ast\AST_NAMESPACE => Namespace_::class,
        \ast\AST_ASSIGN => Assign::class,
        \ast\AST_BINARY_OP => Operation::class,
        \ast\AST_VAR => Variable::class,
        \ast\AST_METHOD_CALL => MethodCall::class,
        \ast\AST_RETURN => Return_::class,
        \ast\AST_THROW => Throw_::class,
        \ast\AST_PRINT => Print_::class,
        \ast\AST_ECHO => Echo_::class,
        \ast\AST_CONST => ConstantUsage::class,
        \ast\AST_NEW => New_::class,
    ];
    const TYPE_TO_NODES = [
        'list' => NodeList::class,
        'class' => Class_::class,
        'namespace' => Namespace_::class,
        'assign' => Assign::class,
        'primitive_value' => PrimitiveValue::class,
        'operation' => Operation::class,
        'variable' => Variable::class,
        'method_call' => MethodCall::class,
        'return' => Return_::class,
        'throw' => Throw_::class,
        'print' => Print_::class,
        'echo' => Echo_::class,
        'constant_usage' => ConstantUsage::class,
        'new' => New_::class,
    ];

    /**
     * Used to serialize the node.
     */
    abstract public function toArray() : array;

    public static function fromArray(array $data, Scope $scope) : Node
    {
        $allNodes = self::TYPE_TO_NODES;

        if (!isset($allNodes[$data['type']])) {
            $class = GenericNode::class;
        } else {
            $class = $allNodes[$data['type']];
        }

        return $class::fromArray($data, $scope);
    }

    abstract public static function fromAstNode(\ast\Node $astNode, Scope $scope) : Node;

    /**
     * Create a node from anything found in the AST of the PHP-AST extension.
     *
     * @param mixed $anything
     */
    public static function fromAst($anything, Scope $scope) : Node
    {
        if (is_scalar($anything)) {
            return PrimitiveValue::fromValue($anything);
        }
        if (! $anything instanceof \ast\Node) {
            throw new \Exception('Unknown node');
        }

        $allNodes = self::AST_TO_NODES;

        if (! isset($allNodes[$anything->kind])) {
            throw new \Exception(\ast\get_kind_name($anything->kind));
            $class = GenericNode::class;
        } else {
            $class = $allNodes[$anything->kind];
        }

        return $class::fromAstNode($anything, $scope);
    }
}
