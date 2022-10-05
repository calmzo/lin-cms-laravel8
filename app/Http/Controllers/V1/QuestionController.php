<?php

namespace App\Http\Controllers\V1;

use App\Services\QuestionService;

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
}
