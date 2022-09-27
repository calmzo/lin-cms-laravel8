<?php

namespace App\Traits;

use App\Caches\UserDailyCounter;
use App\Exceptions\BadRequestException;
use App\Utils\CodeResponse;

trait UserLimitTrait
{

    protected $counter;

    public function __construct()
    {
        $this->counter = new UserDailyCounter();
    }


    public function checkDailyOrderLimit($user)
    {
        $count = $this->counter->hGet($user->id, 'order_count');

        if ($count > 50) {
            throw new BadRequestException(CodeResponse::NOT_FOUND_EXCEPTION, 'user_limit.reach_daily_order_limit');
        }
    }


    public function checkDailyArticleLimit($user)
    {
        $count = $this->counter->hGet($user->id, 'article_count');

        $limit = $user->vip ? 10 : 5;

        if ($count > $limit) {
            throw new BadRequestException(CodeResponse::NOT_FOUND_EXCEPTION, 'user_limit.reach_daily_article_limit');
        }
    }


}
