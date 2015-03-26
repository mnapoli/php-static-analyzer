<?php

namespace PhpAnalyzer\Parser\Visitor;

use PhpAnalyzer\File;
use PhpAnalyzer\Parser\Context;
use PhpAnalyzer\Parser\Node\ReflectedNode;
use PhpAnalyzer\Visitor\ProjectVisitor;
use PhpParser\Node;
use PhpParser\NodeVisitorAbstract;

/**
 * Link nodes to their file.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class LinkToFileVisitor extends NodeVisitorAbstract implements ProjectVisitor
{
    /**
     * @var File
     */
    private $currentFile;

    public function setFile(File $file)
    {
        $this->currentFile = $file;
    }

    public function setContext(Context $context)
    {
    }

    public function enterNode(Node $node)
    {
        if ($node instanceof ReflectedNode) {
            $node->setFile($this->currentFile);
        }
    }
}
