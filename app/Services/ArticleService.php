<?php

namespace App\Services;

use App\Enums\ArticleEnums;
use App\Models\Article;

class ArticleService
{

    public function countArticles()
    {
        return Article::query()->where('published', ArticleEnums::PUBLISH_APPROVED)->count();
    }
}
