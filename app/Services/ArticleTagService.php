<?php

namespace App\Services;

use App\Models\Article;
use App\Models\ArticleTag;
use App\Models\Tag;

class ArticleTagService
{

    public function findArticleTag($articleId, $tagId)
    {
        return ArticleTag::query()->where('article_id', $articleId)->where('tag_id', $tagId)->first();
    }
}
