<?php

namespace App\Lib\Validators;

use App\Enums\OrderEnums;
use App\Exceptions\BadRequestException;
use App\Models\Order as OrderModel;
use App\Models\Refund as RefundModel;
use App\Models\Trade as TradeModel;
use App\Repos\Course as CourseRepo;
use App\Repos\Order as OrderRepo;
use App\Repos\Package as PackageRepo;
use App\Repos\Reward as RewardRepo;
use App\Repos\Vip as VipRepo;
use App\Repositories\CourseRepository;
use App\Repositories\OrderRepository;
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
        $packageRepo = new PackageRepo();

        $package = $packageRepo->findById($itemId);

        if (!$package || $package->published == 0) {
            throw new BadRequestException('order.item_not_found');
        }

        return $package;
    }

    public function checkVip($itemId)
    {
        $vipRepo = new VipRepo();

        $vip = $vipRepo->findById($itemId);

        if (!$vip || $vip->deleted == 1) {
            throw new BadRequestException('order.item_not_found');
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

    public function checkIfAllowPay(OrderModel $order)
    {
        if ($order->status != OrderModel::STATUS_PENDING) {
            throw new BadRequestException('order.pay_not_allowed');
        }
    }

    public function checkIfAllowCancel(OrderModel $order)
    {
        if ($order->status != OrderModel::STATUS_PENDING) {
            throw new BadRequestException('order.cancel_not_allowed');
        }
    }

    public function checkIfAllowRefund(OrderModel $order)
    {
        if ($order->status != OrderModel::STATUS_FINISHED) {
            throw new BadRequestException('order.refund_not_allowed');
        }

        $types = [
            OrderModel::ITEM_COURSE,
            OrderModel::ITEM_PACKAGE,
        ];

        if (!in_array($order->item_type, $types)) {
            throw new BadRequestException('order.refund_item_unsupported');
        }

        $orderRepo = new OrderRepo();

        $trade = $orderRepo->findLastTrade($order->id);

        if ($trade->status != TradeModel::STATUS_FINISHED) {
            throw new BadRequestException('order.refund_not_allowed');
        }

        $refund = $orderRepo->findLastRefund($order->id);

        $scopes = [
            RefundModel::STATUS_PENDING,
            RefundModel::STATUS_APPROVED,
        ];

        if ($refund && in_array($refund->status, $scopes)) {
            throw new BadRequestException('order.refund_apply_existed');
        }
    }

    public function checkIfBoughtCourse($userId, $courseId)
    {
        $orderRepo = new OrderRepo();

        $itemType = OrderModel::ITEM_COURSE;

        $order = $orderRepo->findUserLastDeliveringOrder($userId, $courseId, $itemType);

        if ($order) {
            throw new BadRequestException('order.is_delivering');
        }

        $order = $orderRepo->findUserLastFinishedOrder($userId, $courseId, $itemType);

        if ($order && $order->item_info['course']['study_expiry_time'] > time()) {
            throw new BadRequestException('order.has_bought_course');
        }
    }

    public function checkIfBoughtPackage($userId, $packageId)
    {
        $orderRepo = new OrderRepo();

        $itemType = OrderModel::ITEM_PACKAGE;

        $order = $orderRepo->findUserLastDeliveringOrder($userId, $packageId, $itemType);

        if ($order) {
            throw new BadRequestException('order.is_delivering');
        }

        $order = $orderRepo->findUserLastFinishedOrder($userId, $packageId, $itemType);

        if ($order) {
            throw new BadRequestException('order.has_bought_package');
        }
    }

}
