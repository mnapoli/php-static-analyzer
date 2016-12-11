<?php
declare(strict_types = 1);

namespace PhpAnalyzer\Node\Declaration;

use PhpAnalyzer\Node\HasType;
use PhpAnalyzer\Node\Node;
use PhpAnalyzer\Type\Type;
use PhpAnalyzer\Type\UnknownType;
use PhpAnalyzer\Visibility\Visibility;

/**
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class ClassProperty extends Node implements HasType
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

    public function __construct(string $name, string $docComment = null, Visibility $visibility)
    {
        $this->name = $name;
        $this->docComment = $docComment;
        $this->visibility = $visibility;
    }

    public function getName() : string
    {
        return $this->name;
    }

    public function getReturnType() : Type
    {
        // TODO read type from phpdoc
        return new UnknownType();
    }

    public function toArray() : array
    {
        return [
            'name' => $this->getName(),
            'docComment' => $this->docComment,
            'visibility' => $this->visibility->getValue(),
        ];
    }

    public static function fromArray(array $data) : Node
    {
        return new self($data['name'], $data['docComment'], new Visibility($data['visibility']));
    }

    public static function fromAstNode(\ast\Node $astNode) : Node
    {
        if ($astNode->kind !== \ast\AST_PROP_DECL) {
            throw new \Exception('Wrong type: ' . \ast\get_kind_name($astNode->kind));
        }

        $name = $astNode->children[0]->children['name'];
        $docComment = $astNode->children[0]->docComment ?? null;
        $visibility = Visibility::fromFlags($astNode->flags);

        return new self($name, $docComment, $visibility);
    }
}
