<?php

namespace App\Http\Controllers\V1;

use App\Services\ReviewService;

class ReviewController extends BaseController
{
    protected $except = [];

    public function getReview($id)
    {
        $service = new ReviewService();
        $review = $service->getReview($id);
        return $this->success($review);
    }


}
