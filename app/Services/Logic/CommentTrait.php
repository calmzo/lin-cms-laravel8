<?php

namespace App\Services\Logic;

use App\Validators\CommentValidator;

trait CommentTrait
{

    public function checkComment($id)
    {
        $validator = new CommentValidator();

        return $validator->checkComment($id);
    }

}
