<?php

namespace App\Services;

use App\Models\Topic;

class TopicService
{

    public function countTopics()
    {
        return Topic::query()->where('published', 1)->count();
    }
}
