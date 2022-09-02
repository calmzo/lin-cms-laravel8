<?php

namespace App\Traits;

use App\Enums\TradeEnums;
use App\Exceptions\BadRequestException;
use App\Models\Trade;
use App\Services\Token\LoginTokenService;
use App\Utils\CodeResponse;

trait TradeTrait
{
    use OrderTrait;
    public function checkChannel($channel)
    {
        $list = TradeEnums::channelTypes();

        if (!array_key_exists($channel, $list)) {
            throw  new BadRequestException(CodeResponse::NOT_FOUND_EXCEPTION, '无效的平台类型');
        }

        return $channel;
    }

    public function createHandle()
    {
        $params = request()->all();
        $orderSn = $params['order_sn'];
        $channel = $params['channel'];
        $order = $this->checkOrderBySn($orderSn);
        $this->checkIfAllowPay($order);
        $channel = $this->checkChannel($channel);
        $tradeData = [
            'subject' => $order->subject,
            'amount' => $order->amount,
            'channel' => $channel,
            'order_id' => $order->id,
            'sn' => $order->sn,
            'user_id' => LoginTokenService::userId(),
        ];

        return Trade::query()->create($tradeData);
    }

}
