<?php

namespace PhpAnalyzer\Parser\Node;

use PhpAnalyzer\File;
use PhpParser\Node;

/**
 * Improves the default nodes.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
trait BaseNode
{
    /**
     * @var File|null
     */
    private $file;

    /**
     * @return File|null
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @param File $file
     */
    public function setFile($file)
    {
        $this->file = $file;
    }

    /**
     * @param Node $node
     * @return Node[]
     */
    protected function getSubNodes(Node $node)
    {
        $subNodeNames = $node->getSubNodeNames();
        $subNodes = [];

        foreach ($subNodeNames as $name) {
            $subNodes[$name] = $node->$name;
        }

        return $subNodes;
    }
}
