<?php
declare(strict_types = 1);

namespace PhpAnalyzer\Node\Declaration;

use ast\Node\Decl;
use PhpAnalyzer\Node\TypedNode;
use PhpAnalyzer\Node\Node;
use PhpAnalyzer\Type\Type;
use PhpAnalyzer\Type\UnknownType;
use PhpAnalyzer\Visibility\Visibility;

/**
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class ClassMethod extends Node implements TypedNode
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string|null
     */
    private $docComment;

    /**
     * @var Visibility
     */
    private $visibility;

    /**
     * @var Argument[]
     */
    private $arguments;

    /**
     * @param Argument[] $arguments
     */
    public function __construct(string $name, string $docComment = null, Visibility $visibility, array $arguments)
    {
        $this->name = $name;
        $this->docComment = $docComment;
        $this->visibility = $visibility;
        $this->arguments = $arguments;
    }

    public function getName() : string
    {
        return $this->name;
    }

    public function getChildren() : array
    {
        return $this->arguments;
    }

    public function getReturnType() : Type
    {
        // TODO read type from type-hint or phpdoc
        return new UnknownType();
    }

    public function toArray() : array
    {
        return [
            'name' => $this->getName(),
            'docComment' => $this->docComment,
            'visibility' => $this->visibility->getValue(),
            'arguments' => array_map(function (Argument $argument) {
                return $argument->toArray();
            }, $this->arguments),
        ];
    }

    public static function fromArray(array $data) : Node
    {
        return new self(
            $data['name'],
            $data['docComment'],
            new Visibility($data['visibility']),
            array_map(function (array $data) {
                return Argument::fromArray($data);
            }, $data['arguments'])
        );
    }

    public static function fromAstNode(\ast\Node $astNode) : Node
    {
        if ($astNode->kind !== \ast\AST_METHOD || !$astNode instanceof Decl) {
            throw new \Exception('Wrong type: ' . \ast\get_kind_name($astNode->kind));
        }

        $name = $astNode->name;
        $docComment = $astNode->docComment;
        $visibility = Visibility::fromFlags($astNode->flags);

        $arguments = array_map(function (\ast\Node $astNode) {
            return Argument::fromAstNode($astNode);
        }, $astNode->children['params']->children);

        return new self($name, $docComment, $visibility, $arguments);
    }
}
