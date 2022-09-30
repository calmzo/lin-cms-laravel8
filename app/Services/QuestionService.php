<?php

namespace App\Services;

use App\Enums\QuestionEnums;
use App\Models\Question;

class QuestionService
{

    public function countQuestions()
    {
        return Question::query()->where('published', QuestionEnums::PUBLISH_APPROVED)->count();
    }

    public function findByIds($ids, $columns = '*')
    {
        return Question::query()
            ->whereIn('id', $ids)
            ->get($columns);
    }
}
