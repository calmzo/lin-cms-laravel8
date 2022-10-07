<?php

namespace App\Http\Controllers\V1;

use Illuminate\Http\Request;
use App\Services\AnswerService;
use App\Validates\Answer\AnswerFormValidate;

class AnswerController extends BaseController
{
    //
    protected $except = [];

    public function getAnswer($id)
    {
        $service = new AnswerService();
        $answer = $service->getAnswer($id);
        return $this->success($answer);
    }

    public function createAnswer(AnswerFormValidate $answerFormValidate)
    {
        $params = $answerFormValidate->check();
        $service = new AnswerService();
        $answer = $service->createAnswer($params);
        return $this->success($answer);
    }

    public function updateAnswer($id, AnswerFormValidate $answerFormValidate)
    {
        $params = $answerFormValidate->check();
        $service = new AnswerService();
        $answer = $service->updateAnswer($id, $params);
        return $this->success($answer);
    }


    public function getComments($id, Request $request)
    {
        $params = $request->all();
        $service = new AnswerService();
        $answer = $service->getComments($id, $params);
        return $this->success($answer);
    }

    public function deleteAnswer($id)
    {
        $service = new AnswerService();
        $service->deleteAnswer($id);
        return $this->success();
    }

    public function acceptAnswer($id)
    {
        $service = new AnswerService();
        $res = $service->acceptAnswer($id);
        return $this->success($res);
    }


    public function likeAnswer($id)
    {
        $service = new AnswerService();
        $res = $service->likeAnswer($id);
        return $this->success($res);
    }

}
