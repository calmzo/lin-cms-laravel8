<?php

namespace App\Validators;

use App\Enums\OrderEnums;
use App\Enums\RefundEnums;
use App\Enums\TradeEnums;
use App\Exceptions\BadRequestException;
use App\Models\Order;
use App\Models\Refund;
use App\Models\Trade;
use App\Repositories\CourseRepository;
use App\Repositories\OrderRepository;
use App\Repositories\PackageRepository;
use App\Repositories\VipRepository;
use App\Utils\CodeResponse;

class OrderValidator extends BaseValidator
{

    public function checkOrder($id)
    {
        return $this->checkOrderById($id);
    }

    public function checkOrderById($id)
    {
        $orderRepo = new OrderRepository();

        $order = $orderRepo->findById($id);

        if (!$order) {
            throw new BadRequestException(CodeResponse::NOT_FOUND_EXCEPTION, 'order.not_found');
        }

        return $order;
    }

    public function checkOrderBySn($sn)
    {
        $orderRepo = new OrderRepository();

        $order = $orderRepo->findBySn($sn);

        if (!$order) {
            throw new BadRequestException(CodeResponse::NOT_FOUND_EXCEPTION, 'order.not_found');
        }

        return $order;
    }

    public function checkItemType($itemType)
    {
        $list = OrderEnums::itemTypes();

        if (!array_key_exists($itemType, $list)) {
            throw new BadRequestException(CodeResponse::NOT_FOUND_EXCEPTION, 'order.invalid_item_type');
        }

        return $itemType;
    }

    public function checkCourse($itemId)
    {
        $courseRepo = new CourseRepository();

        $course = $courseRepo->findById($itemId);

        if (!$course || $course->published == 0) {
            throw new BadRequestException(CodeResponse::NOT_FOUND_EXCEPTION, 'order.item_not_found');
        }

        return $course;
    }

    public function checkPackage($itemId)
    {
        $packageRepo = new PackageRepository();

        $package = $packageRepo->findById($itemId);

        if (!$package || $package->published == 0) {
            throw new BadRequestException(CodeResponse::NOT_FOUND_EXCEPTION, 'order.item_not_found');
        }

        return $package;
    }

    public function checkVip($itemId)
    {
        $vipRepo = new VipRepository();

        $vip = $vipRepo->findById($itemId);

        if (!$vip) {
            throw new BadRequestException(CodeResponse::NOT_FOUND_EXCEPTION, 'order.item_not_found');
        }

        return $vip;
    }

    public function checkReward($itemId)
    {
        $rewardRepo = new RewardRepo();

        $reward = $rewardRepo->findById($itemId);

        if (!$reward) {
            throw new BadRequestException('order.item_not_found');
        }

        return $reward;
    }

    public function checkAmount($amount)
    {
        $value = $this->filter->sanitize($amount, ['trim', 'float']);

        if ($value < 0.01 || $value > 10000) {
            throw new BadRequestException('order.invalid_pay_amount');
        }

        return $value;
    }

    public function checkStatus($status)
    {
        $list = OrderEnums::statusTypes();

        if (!array_key_exists($status, $list)) {
            throw new BadRequestException(CodeResponse::NOT_FOUND_EXCEPTION, 'order.invalid_status');
        }

        return $status;
    }

    public function checkIfAllowPay(Order $order)
    {
        if ($order->status != OrderEnums::STATUS_PENDING) {
            throw new BadRequestException(CodeResponse::NOT_FOUND_EXCEPTION, 'order.pay_not_allowed');
        }
    }

    public function checkIfAllowCancel(Order $order)
    {
        if ($order->status != OrderEnums::STATUS_PENDING) {
            throw new BadRequestException(CodeResponse::NOT_FOUND_EXCEPTION, 'order.cancel_not_allowed');
        }
    }

    public function checkIfAllowRefund(Order $order)
    {
        if ($order->status != OrderEnums::STATUS_FINISHED) {
            throw new BadRequestException(CodeResponse::NOT_FOUND_EXCEPTION, 'order.refund_not_allowed');
        }

        $types = [
            OrderEnums::ITEM_COURSE,
            OrderEnums::ITEM_PACKAGE,
        ];

        if (!in_array($order->item_type, $types)) {
            throw new BadRequestException(CodeResponse::NOT_FOUND_EXCEPTION, 'order.refund_item_unsupported');
        }

        $orderRepo = new OrderRepository();

        $trade = $orderRepo->findLastTrade($order->id);

        if ($trade->status != TradeEnums::STATUS_FINISHED) {
            throw new BadRequestException(CodeResponse::NOT_FOUND_EXCEPTION, 'order.refund_not_allowed');
        }

        $refund = $orderRepo->findLastRefund($order->id);

        $scopes = [
            RefundEnums::STATUS_PENDING,
            RefundEnums::STATUS_APPROVED,
        ];

        if ($refund && in_array($refund->status, $scopes)) {
            throw new BadRequestException(CodeResponse::NOT_FOUND_EXCEPTION, 'order.refund_apply_existed');
        }
    }

    public function checkIfBoughtCourse($userId, $courseId)
    {
        $orderRepo = new OrderRepository();

        $itemType = OrderEnums::ITEM_COURSE;

        $order = $orderRepo->findUserLastDeliveringOrder($userId, $courseId, $itemType);

        if ($order) {
            throw new BadRequestException(CodeResponse::NOT_FOUND_EXCEPTION, 'order.is_delivering');
        }

        $order = $orderRepo->findUserLastFinishedOrder($userId, $courseId, $itemType);

        if ($order && $order->item_info['course']['study_expiry_time'] > time()) {
            throw new BadRequestException(CodeResponse::NOT_FOUND_EXCEPTION, 'order.has_bought_course');
        }
    }

    public function checkIfBoughtPackage($userId, $packageId)
    {
        $orderRepo = new OrderRepository();

        $itemType = OrderEnums::ITEM_PACKAGE;

        $order = $orderRepo->findUserLastDeliveringOrder($userId, $packageId, $itemType);

        if ($order) {
            throw new BadRequestException(CodeResponse::NOT_FOUND_EXCEPTION, 'order.is_delivering');
        }

        $order = $orderRepo->findUserLastFinishedOrder($userId, $packageId, $itemType);

        if ($order) {
            throw new BadRequestException(CodeResponse::NOT_FOUND_EXCEPTION, 'order.has_bought_package');
        }
    }

}
