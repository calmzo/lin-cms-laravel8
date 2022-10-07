<?php

namespace App\Http\Controllers\V1;

use App\Services\ChapterService;
use App\Validates\Chapter\ChapterLearningValidate;
use Illuminate\Http\Request;

class ChapterController extends BaseController
{
    //
    protected $except = [];

    public function getComments($id, Request $request)
    {
        $params = $request->all();
        $service = new ChapterService();
        $answer = $service->getComments($id, $params);
        return $this->success($answer);
    }

    public function getConsults($id, Request $request)
    {
        $params = $request->all();
        $service = new ChapterService();
        $answer = $service->getConsults($id, $params);
        return $this->success($answer);
    }

    public function getResources($id)
    {
        $service = new ChapterService();
        $res = $service->getResources($id);
        return $this->success($res);
    }

    public function getChapter($id)
    {
        $service = new ChapterService();
        $res = $service->getChapter($id);
        return $this->success($res);
    }

    public function likeChapter($id)
    {
        $service = new ChapterService();
        $res = $service->likeChapter($id);
        return $this->success($res);
    }

    public function learningChapter($id, ChapterLearningValidate $chapterLearningValidate)
    {
        $params = $chapterLearningValidate->check();
        $service = new ChapterService();
        $res = $service->learningChapter($id, $params);
        return $this->success($res);
    }
}
