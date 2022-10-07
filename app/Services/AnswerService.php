<?php

namespace App\Services;

use App\Enums\AnswerEnums;
use App\Exceptions\NotFoundException;
use App\Services\Logic\Answer\AnswerAcceptService;
use App\Services\Logic\Answer\AnswerCreateService;
use App\Services\Logic\Answer\AnswerDeleteService;
use App\Services\Logic\Answer\AnswerInfoService;
use App\Services\Logic\Answer\AnswerLikeService;
use App\Services\Logic\Answer\AnswerUpdateService;
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

    public function updateAnswer($id, $params)
    {
        $service = new AnswerUpdateService();

        $answer = $service->handle($id, $params);

        return ['answer' => $answer];
    }

    public function getComments($id, $params)
    {
        $service = new CommentListService();

        $pager = $service->handle($id, $params);
        return $pager;
    }

    public function deleteAnswer($id)
    {
        $service = new AnswerDeleteService();

        $service->handle($id);

        return true;
    }

    public function acceptAnswer($id)
    {
        $service = new AnswerAcceptService();

        $data = $service->handle($id);
        $msg = $data['action'] == 'do' ? '采纳成功' : '取消采纳成功';

        return ['data' => $data, 'msg' => $msg];
    }

    public function likeAnswer($id)
    {
        $service = new AnswerLikeService();

        $data = $service->handle($id);

        $msg = $data['action'] == 'do' ? '点赞成功' : '取消点赞成功';

        return ['data' => $data, 'msg' => $msg];
    }

}
