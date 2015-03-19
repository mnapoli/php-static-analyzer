<?php

namespace PhpAnalyzer\Test\Fixture;

class Blog
{
    /**
     * @var Article[]
     */
    private $articles = [];

    public function addArticle(Article $article)
    {
        $this->articles[] = $article;
    }

    public function publish($title, $content)
    {
        $article = new Article($this);

        $this->save($article);
    }

    private function save(Article $article)
    {
    }
}
