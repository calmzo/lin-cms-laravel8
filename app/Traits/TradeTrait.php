<?php

namespace App\Traits;

use App\Enums\RefundEnums;
use App\Enums\TradeEnums;
use App\Exceptions\BadRequestException;
use App\Models\Refund;
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

    /**
     * 查询是否退款
     * @param Trade $trade
     * @throws BadRequestException
     */
    public function checkIfAllowRefund(Trade $trade)
    {
        if ($trade->status != TradeEnums::STATUS_FINISHED) {
            throw new BadRequestException(CodeResponse::NOT_FOUND_EXCEPTION, '当前不允许交易退款');
        }

        $refund = Refund::query()->where('trade_id', $trade->id)->orderByDesc('id')->first();

        $scopes = [
            RefundEnums::STATUS_PENDING,
            RefundEnums::STATUS_APPROVED,
        ];

        if ($refund && in_array($refund->status, $scopes)) {
            throw new BadRequestException(CodeResponse::NOT_FOUND_EXCEPTION, '退款申请已经存在，请等待处理结果');
        }
    }

    public function checkTrade($id)
    {
        $trade = Trade::query()->find($id);
        if (!$trade) {
            throw new BadRequestException(CodeResponse::NOT_FOUND_EXCEPTION, '交易不存在');
        }

        return $trade;
    }
}
