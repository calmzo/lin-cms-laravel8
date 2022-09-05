<?php

namespace App\Traits;

use App\Enums\OrderEnums;
use App\Exceptions\BadRequestException;
use App\Models\Course;
use App\Models\Order;
use App\Models\Vip;
use App\Utils\CodeResponse;

trait OrderTrait
{
    public function checkAmount($amount)
    {

        if ($amount < 0.01 || $amount > 10000) {
            throw new BadRequestException(CodeResponse::NOT_FOUND_EXCEPTION, '金额无效');

        }

        return $amount;
    }

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


    public function findUserLastPendingOrder($userId, $itemId, $itemType)
    {
        $status = OrderEnums::STATUS_PENDING;

        return $this->findUserLastStatusOrder($userId, $itemId, $itemType, $status);
    }

    public function findUserLastStatusOrder($userId, $itemId, $itemType, $status)
    {
        return Order::query()
            ->where('user_id', $userId)
            ->where('item_id', $itemId)
            ->where('item_type', $itemType)
            ->where('status', $status)
            ->orderByDesc('id')
            ->first();
    }

    //是否会员
    public function checkVip($itemId)
    {

        $vip = Vip::query()->find($itemId);
        if (!$vip) {
            throw new BadRequestException(CodeResponse::NOT_FOUND_EXCEPTION, '商品不存在');
        }

        return $vip;
    }

    //是否课程
    public function checkCourse($itemId)
    {
        $course = Course::query()->find($itemId);

        if (!$course || $course->published == 0) {
            throw new BadRequestException(CodeResponse::NOT_FOUND_EXCEPTION, '商品不存在');
        }

        return $course;
    }

    //下单校验
    public function checkIfBoughtCourse($userId, $courseId)
    {
        $itemType = OrderEnums::ITEM_COURSE;

        $order = $this->findUserLastDeliveringOrder($userId, $courseId, $itemType);

        if ($order) {
            throw new BadRequestException(CodeResponse::NOT_FOUND_EXCEPTION, '已经下过单了，正在准备发货中');
        }

        $order = $this->findUserLastFinishedOrder($userId, $courseId, $itemType);

        if ($order && $order->item_info['course']['study_expiry_time'] > time()) {
            throw new BadRequestException(CodeResponse::NOT_FOUND_EXCEPTION, '已经购买过该课程');
        }
    }

    public function findUserLastDeliveringOrder($userId, $itemId, $itemType)
    {
        $status = OrderEnums::STATUS_DELIVERING;

        return $this->findUserLastStatusOrder($userId, $itemId, $itemType, $status);
    }

    public function findUserLastFinishedOrder($userId, $itemId, $itemType)
    {
        $status = OrderEnums::STATUS_FINISHED;

        return $this->findUserLastStatusOrder($userId, $itemId, $itemType, $status);
    }

}
