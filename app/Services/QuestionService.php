<?php

namespace App\Services;

use App\Enums\QuestionEnums;
use App\Exceptions\NotFoundException;
use App\Services\Logic\Question\QuestionInfoService;
use App\Services\Logic\Question\QuestionListService;

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

    public function getQuestions($params)
    {
        $service = new QuestionListService();

        $pager = $service->handle($params);

        return $pager;

    }
}
