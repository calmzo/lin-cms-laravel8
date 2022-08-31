<?php

namespace App\Traits;

use App\Enums\OrderEnums;
use App\Enums\TradeEnums;
use App\Exceptions\BadRequestException;
use App\Models\Order;
use App\Utils\CodeResponse;

trait TradeTrait
{
    public function checkChannel($channel)
    {
        $list = TradeEnums::channelTypes();

        if (!array_key_exists($channel, $list)) {
            throw  new BadRequestException(CodeResponse::NOT_FOUND_EXCEPTION, '无效的平台类型');
        }

        return $channel;
    }

}
