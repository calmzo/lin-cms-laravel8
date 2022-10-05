<?php

namespace App\Http\Controllers\V1;

use App\Services\QuestionService;
use App\Validates\Question\AnswerListValidate;
use App\Validates\Question\CommentListValidate;
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

    public function getCategories()
    {
        $service = new QuestionService();
        return $service->getCategories();
    }

    public function getAnswers($id, AnswerListValidate $answerListValidate)
    {
        $params = $answerListValidate->check();
        $service = new QuestionService();
        return $service->getAnswers($id, $params);
    }

    public function getComments($id, CommentListValidate $commentListValidate)
    {
        $params = $commentListValidate->check();
        $service = new QuestionService();
        return $service->getComments($id, $params);
    }
}
