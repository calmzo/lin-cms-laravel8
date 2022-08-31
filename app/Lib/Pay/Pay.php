<?php

namespace App\Lib\Pay;

use App\Models\Refund as RefundModel;
use App\Models\Trade as TradeModel;

abstract class Pay
{

    /**
     * 交易状态
     *
     * @param string $tradeNo
     * @return int
     */
    public function status($tradeNo)
    {
        $trade = TradeModel::query()->where('sn', $tradeNo)->first();
        return $trade->status;
    }

    /**
     * 扫码下单
     *
     * @param TradeModel $trade
     */
    abstract public function scan(TradeModel $trade);

    /**
     * wap下单
     *
     * @param TradeModel $trade
     */
    abstract public function wap(TradeModel $trade);

    /**
     * 异步通知
     */
    abstract public function notify();

    /**
     * 查找交易
     *
     * @param string $tradeNo
     * @param string $type
     */
    abstract public function find($tradeNo, $type);

    /**
     * 关闭交易
     *
     * @param string $tradeNo
     */
    abstract public function close($tradeNo);

    /**
     * 取消交易
     *
     * @param string $tradeNo
     */
    abstract public function cancel($tradeNo);

    /**
     * 申请退款
     *
     * @param RefundModel $refund
     */
    abstract public function refund(RefundModel $refund);

}
