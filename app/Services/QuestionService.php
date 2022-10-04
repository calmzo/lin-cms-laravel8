<?php

namespace App\Services;

use App\Models\Question;

class QuestionService
{
    public function findByIds($ids, $columns = '*')
    {
        return Question::query()
            ->whereIn('id', $ids)
            ->get($columns);
    }
}
