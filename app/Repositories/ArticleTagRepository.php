<?php

namespace App\Repositories;

use App\Models\ArticleTag;

class ArticleTagRepository extends BaseRepository
{
    
    public function findByTagIds($tagIds)
    {
        return ArticleTag::query()->whereIn('tag_id', $tagIds)->get();
    }
}
