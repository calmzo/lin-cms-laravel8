<?php

namespace App\Http\Controllers\V1;

use App\Services\AnswerService;

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

}
