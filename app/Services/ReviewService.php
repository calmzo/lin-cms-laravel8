<?php

namespace App\Services;

use App\Enums\ReviewEnums;
use App\Models\Review;

class ReviewService
{

    public function countReviews()
    {
        return Review::query()->where('published', ReviewEnums::PUBLISH_APPROVED)->count();
    }
}
