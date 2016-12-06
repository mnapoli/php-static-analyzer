<?php
declare(strict_types = 1);

namespace PhpAnalyzer\Node;

use ast\Node\Decl;
use PhpAnalyzer\Visibility\Visibility;

/**
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class ClassMethod extends Node
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
        return new self(
            $data['name'],
            $data['docComment'],
            new Visibility($data['visibility'])
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

        return new self($name, $docComment, $visibility);
    }
}
