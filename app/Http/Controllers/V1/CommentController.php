<?php

namespace App\Http\Controllers\V1;

use App\Services\ChapterService;
use App\Services\CommentService;
use Illuminate\Http\Request;

class CommentController extends BaseController
{
    //
    protected $only = ['getComment'];

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

}
