<?php

namespace App\Traits;

use App\Enums\OrderEnums;
use App\Exceptions\BadRequestException;
use App\Models\Order;
use App\Utils\CodeResponse;

trait OrderTrait
{
    public function checkOrderById($id)
    {
        $order = Order::query()->find($id);

        if (!$order) {
            throw new BadRequestException(CodeResponse::NOT_FOUND_EXCEPTION, '订单不存在');
        }
        return $order;
    }

    public function checkOrderBySn($sn)
    {
        $order = Order::query()->where('sn', $sn)->first();

        if (!$order) {
            throw new BadRequestException(CodeResponse::NOT_FOUND_EXCEPTION, '订单不存在');
        }
        return $order;
    }

    public function checkIfAllowPay(Order $order)
    {
        if ($order->status != OrderEnums::STATUS_PENDING) {
            throw new BadRequestException(CodeResponse::NOT_FOUND_EXCEPTION, '当前不允许支付');
        }
    }

}
