<?php

namespace App\Traits;

use App\Caches\UserDailyCounter;
use App\Exceptions\BadRequestException;
use App\User;
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



}
