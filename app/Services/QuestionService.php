<?php

namespace App\Services;

use App\Enums\QuestionEnums;
use App\Exceptions\NotFoundException;
use App\Services\Logic\Question\QuestionInfoService;

class QuestionService
{
    public function getQuestion($id)
    {
        $service = new QuestionInfoService();

        $question = $service->handle($id);

        $approved = $question['published'] == QuestionEnums::PUBLISH_APPROVED;
        $owned = $question['me']['owned'] == 1;

        if (!$approved && !$owned) {
            throw new NotFoundException();
        }
        return ['question' => $question];

    }
}
