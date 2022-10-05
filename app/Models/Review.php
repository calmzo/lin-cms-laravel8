<?php

namespace App\Models;

class Review extends BaseModel
{
    public $fillable = [
        'client_type', 'client_ip', 'course_id', 'user_id', 'content', 'rating', 'rating1', 'rating2', 'rating3', 'published'
    ];
    protected static function booted()
    {
        //处理 Review「saving」事件
        static::saving(function ($review) {
            $review->rating = self::getAvgRating($review);
        });
    }


    public static function getAvgRating($review)
    {
        $sumRating = $review->rating1 + $review->rating2 + $review->rating3;

        return round($sumRating / 3, 2);
    }
}


