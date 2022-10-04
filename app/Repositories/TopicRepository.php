<?php

namespace App\Repositories;

use App\Models\Topic;

class TopicRepository extends BaseRepository
{
    public function countTopics()
    {
        return Topic::query()->where('published', 1)->count();
    }
}
