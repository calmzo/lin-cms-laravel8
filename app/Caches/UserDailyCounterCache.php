<?php

namespace App\Caches;

class UserDailyCounterCache extends Cache
{

    protected $lifetime = 1 * 86400;

    public function getLifetime()
    {
        $tomorrow = strtotime('tomorrow');

        return $tomorrow - time();
    }

    public function getKey($id = null)
    {
        return "user_daily_counter:{$id}";
    }

    public function getContent($id = null)
    {
        return [
            'article_count' => 0,
            'question_count' => 0,
            'answer_count' => 0,
            'comment_count' => 0,
            'consult_count' => 0,
            'order_count' => 0,
            'chapter_like_count' => 0,
            'consult_like_count' => 0,
            'review_like_count' => 0,
            'article_like_count' => 0,
            'question_like_count' => 0,
            'answer_like_count' => 0,
            'comment_like_count' => 0,
        ];
    }

}
