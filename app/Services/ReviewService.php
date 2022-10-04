<?php

namespace App\Services;

use App\Enums\ReviewEnums;
use App\Exceptions\NotFoundException;
use App\Services\Logic\Review\ReviewInfoService;

class ReviewService
{
    public function getReview($id)
    {
        $service = new ReviewInfoService();

        $review = $service->handle($id);
        $approved = $review['published'] == ReviewEnums::PUBLISH_APPROVED;
        $owned = $review['me']['owned'] == 1;

        if (!$approved && !$owned) {
            throw new NotFoundException();
        }
        return ['review' => $review];


    }

}
