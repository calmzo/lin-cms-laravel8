<?php

namespace App\Services;

use App\Enums\AnswerEnums;
use App\Models\Answer;

class AnswerService
{

    public function countAnswers()
    {
        return Answer::query()->where('published', AnswerEnums::PUBLISH_APPROVED)->count();
    }
}
