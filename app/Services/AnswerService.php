<?php

namespace App\Services;

use App\Enums\AnswerEnums;
use App\Exceptions\NotFoundException;
use App\Services\Logic\Answer\AnswerInfoService;

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

}
