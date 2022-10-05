<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Review extends BaseModel
{
    use BooleanSoftDeletes, HasFactory;

    public $fillable = [
        'client_type', 'client_ip', 'course_id', 'user_id', 'content', 'rating', 'rating1', 'rating2', 'rating3', 'published'
    ];
    protected static function booted()
    {
        //处理 Review「creating」事件
        static::creating(function ($review) {
            $review->rating = self::getAvgRating($review);
        });
    }


    public static function getAvgRating($review)
    {
        $sumRating = $review->rating1 + $review->rating2 + $review->rating3;

        return round($sumRating / 3, 2);
    }
}


