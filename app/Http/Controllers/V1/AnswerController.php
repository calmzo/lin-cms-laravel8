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


    public function getComments($id, Request $request)
    {
        $params = $request->all();
        $service = new AnswerService();
        $answer = $service->getComments($id, $params);
        return $this->success($answer);
    }

}
