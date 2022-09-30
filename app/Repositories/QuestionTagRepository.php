<?php

namespace App\Repositories;

use App\Models\QuestionTag;

class QuestionTagRepository extends BaseRepository
{

    public function findByTagIds($tagIds)
    {
        return QuestionTag::query()->whereIn('tag_id', $tagIds)->get();
    }
}
