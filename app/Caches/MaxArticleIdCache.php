<?php

namespace App\Caches;
use App\Models\Article;

class MaxArticleIdCache extends Cache
{

    protected $lifetime = 365 * 86400;

    public function getLifetime()
    {
        return $this->lifetime;
    }

    public function getKey($id = null)
    {
        return 'max_article_id';
    }

    public function getContent($id = null)
    {
        $article = Article::query()->latest('id')->first();

        return $article->id ?? 0;
    }

}
