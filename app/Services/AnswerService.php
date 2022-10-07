<?php

namespace App\Services;

use App\Enums\AnswerEnums;
use App\Exceptions\NotFoundException;
use App\Services\Logic\Answer\AnswerCreateService;
use App\Services\Logic\Answer\AnswerInfoService;
use App\Services\Logic\Answer\CommentListService;

class AnswerService extends BaseService
{
    public function getAnswer($id)
    {
        $service = new AnswerInfoService();

        $answer = $service->handle($id);

        $approved = $answer['published'] == AnswerEnums::PUBLISH_APPROVED;
        $owned = $answer['me']['owned'] == 1;

        if (!$approved && !$owned) {
            throw new NotFoundException();
        }

        return ['answer' => $answer];
    }

    public function createAnswer($params)
    {
        $service = new AnswerCreateService();

        $answer = $service->handle($params);

        $service = new AnswerInfoService();

        $answer = $service->handle($answer->id);

        return ['answer' => $answer];
    }

    public function getComments($id, $params)
    {
        $service = new CommentListService();

        $pager = $service->handle($id, $params);
        return $pager;
    }

}
