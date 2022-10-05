<?php

namespace App\Services;

use App\Enums\ReviewEnums;
use App\Exceptions\NotFoundException;
use App\Services\Logic\Review\ReviewCreateService;
use App\Services\Logic\Review\ReviewInfoService;
use App\Services\Logic\Review\ReviewUpdateService;

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

    public function createReview($params)
    {
        $service = new ReviewCreateService();

        $review = $service->handle($params);

        $service = new ReviewInfoService();
        $review = $service->handle($review->id);
        return $review;
    }

    public function updateReview($id, $params)
    {
        $service = new ReviewUpdateService();

        $service->handle($id, $params);

        $service = new ReviewInfoService();
        $review = $service->handle($id);
        return $review;
    }

}
