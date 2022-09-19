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
}
