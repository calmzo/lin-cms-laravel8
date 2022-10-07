<?php

namespace App\Services;

use App\Exceptions\NotFoundException;
use App\Exceptions\Token\ForbiddenException;
use App\Services\Logic\Chapter\ChapterInfoService;
use App\Services\Logic\Chapter\ChapterLikeService;
use App\Services\Logic\Chapter\CommentListService;
use App\Services\Logic\Chapter\ConsultListService;
use App\Services\Logic\Chapter\LearningService;
use App\Services\Logic\Chapter\ResourceListService;

class ChapterService extends BaseService
{
    public function getComments($id, $params)
    {
        $service = new CommentListService();

        $pager = $service->handle($id, $params);
        return $pager;
    }

    public function getConsults($id, $params)
    {
        $service = new ConsultListService();

        $pager = $service->handle($id, $params);
        return $pager;
    }

    public function getResources($id)
    {
        $service = new ResourceListService();

        $resources = $service->handle($id);
        return ['resources' => $resources];
    }

    public function getChapter($id)
    {
        $service = new ChapterInfoService();

        $chapter = $service->handle($id);
        if ($chapter['published'] == 0) {
            throw new NotFoundException();
        }

        if ($chapter['me']['owned'] == 0) {
            throw new ForbiddenException();
        }
        return ['chapter' => $chapter];
    }

    public function likeChapter($id)
    {
        $service = new ChapterLikeService();

        $data = $service->handle($id);

        $msg = $data['action'] == 'do' ? '点赞成功' : '取消点赞成功';

        return ['data' => $data, 'msg' => $msg];
    }

    public function learningChapter($id, $params)
    {
        $service = new LearningService();

        $service->handle($id, $params);

        return true;
    }

}
