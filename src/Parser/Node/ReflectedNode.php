<?php

namespace PhpAnalyzer\Parser\Node;

use PhpAnalyzer\File;

/**
 * Base interface for all methods exposed by our custom nodes.
 *
 * @see BaseNode for implementations.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
interface ReflectedNode
{
    /**
     * @return File|null
     */
    public function getFile();

    /**
     * @param File $file
     */
    public function setFile($file);
}
