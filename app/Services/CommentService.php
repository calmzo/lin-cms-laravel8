<?php

namespace App\Services;


use App\Services\Logic\Comment\CommentCreateService;
use App\Services\Logic\Comment\CommentDeleteService;
use App\Services\Logic\Comment\CommentInfoService;
use App\Services\Logic\Comment\CommentListService;
use App\Services\Logic\Comment\CommentReplyService;
use App\Services\Logic\Comment\ReplyListService;

class CommentService extends BaseService
{
    public function getReplies($id, $params)
    {
        $service = new ReplyListService();

        $pager = $service->handle($id, $params);
        return $pager;
    }

    public function getComment($id)
    {
        $service = new CommentInfoService();

        $comment = $service->handle($id);

        return ['comment' => $comment];
    }


    public function createComment($params)
    {

        $service = new CommentCreateService();

        $comment = $service->handle($params);

        $service = new CommentInfoService();

        $comment = $service->handle($comment->id);

        return ['comment' => $comment];
    }


    public function getComments($params)
    {
        $service = new CommentListService();

        $pager = $service->handle($params);
        return $pager;
    }


    public function replyComment($id, $params)
    {
        $service = new CommentReplyService();

        $comment = $service->handle($id, $params);

        $service = new CommentInfoService();

        $comment = $service->handle($comment->id);

        return ['comment' => $comment];
    }

    public function deleteComment($id)
    {
        $service = new CommentDeleteService();

        $service->handle($id);

        return true;
    }


}
