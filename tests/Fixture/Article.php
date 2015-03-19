<?php

namespace PhpAnalyzer\Test\Fixture;

class Article
{
    /**
     * @var Blog
     */
    private $blog;

    public function __construct(Blog $blog)
    {
        $this->blog = $blog;
    }

    /**
     * @return Blog
     */
    public function getBlog()
    {
        return $this->blog;
    }
}
