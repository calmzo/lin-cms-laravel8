<?php

namespace App\Services\Logic;

use App\Validators\AnswerValidator;

trait AnswerTrait
{

    public function checkAnswer($id)
    {
        $validator = new AnswerValidator();

        return $validator->checkAnswer($id);
    }

}
