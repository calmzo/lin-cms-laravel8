<?php

namespace App\Http\Controllers\V1;

use App\Services\QuestionService;
use App\Validates\Question\QuestionListValidate;

class QuestionController extends BaseController
{
    //
    protected $except = [];

    public function getQuestion($id)
    {
        $service = new QuestionService();
        $question = $service->getQuestion($id);
        return $this->success($question);
    }

    public function getQuestions(QuestionListValidate $questionListValidate)
    {
        $params = $questionListValidate->check();
        $service = new QuestionService();
        return $service->getQuestions($params);
    }
}
