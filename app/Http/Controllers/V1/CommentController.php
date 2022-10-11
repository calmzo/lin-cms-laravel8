<?php

namespace App\Http\Controllers\V1;

use App\Services\ChapterService;
use App\Services\CommentService;
use Illuminate\Http\Request;

class CommentController extends BaseController
{
    //
    protected $except = ['getReplies'];

    public function getReplies($id, Request $request)
    {
        $params = $request->all();
        $service = new CommentService();
        $pager = $service->getReplies($id, $params);
        return $this->success($pager);
    }

    public function getComment($id)
    {
        $service = new CommentService();
        $pager = $service->getComment($id);
        return $this->success($pager);
    }

    public function createComment(Request $request)
    {
        $params = $request->all();
        $service = new CommentService();
        $pager = $service->createComment($params);
        return $this->success($pager);
    }

    public function replyComment($id, Request $request)
    {
        $params = $request->all();
        $service = new CommentService();
        $res = $service->replyComment($id, $params);
        return $this->success($res);
    }

    public function deleteComment($id)
    {
        $service = new CommentService();
        $service->deleteComment($id);
        return $this->success();
    }

}
