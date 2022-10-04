<?php

namespace App\Traits;

use App\Validators\ReviewValidator;

trait ReviewTrait
{

    public function checkReview($id)
    {
        $validator = new ReviewValidator();

        return $validator->checkReview($id);
    }

}
