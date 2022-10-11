<?php

namespace App\Services;

use App\Enums\OrderEnums;
use App\Enums\TradeEnums;
use App\Models\Order;
use App\Repositories\CourseRepository;
use App\Repositories\CourseUserRepository;
use App\Repositories\OrderRepository;
use App\Services\Logic\Refund\RefundConfirmService;

class RefundService extends BaseService
{

    public function getConfirm($sn)
    {

        $service = new RefundConfirmService();

        $confirm = $service->handle($sn);

        return ['confirm' => $confirm];
    }


    public function preview(Order $order)
    {
        $result = [
            'item_type' => 0,
            'item_info' => [],
            'refund_amount' => 0.00,
            'service_fee' => 0.00,
            'service_rate' => 5.00,
        ];

        switch ($order->item_type) {
            case OrderEnums::ITEM_COURSE:
                $result = $this->previewCourseRefund($order);
                break;
            case OrderEnums::ITEM_PACKAGE:
                $result = $this->previewPackageRefund($order);
                break;
            case OrderEnums::ITEM_REWARD:
                $result = $this->previewRewardRefund($order);
                break;
            case OrderEnums::ITEM_VIP:
                $result = $this->previewVipRefund($order);
                break;
            case OrderEnums::ITEM_TEST:
                $result = $this->previewTestRefund($order);
                break;
        }

        return $result;
    }


    protected function previewCourseRefund(Order $order)
    {
        $itemInfo = $order->item_info;

//        $itemInfo['course']['cover'] = kg_cos_course_cover_url($itemInfo['course']['cover']);

        $serviceFee = $this->getServiceFee($order);
        $serviceRate = $this->getServiceRate($order);

        $refundPercent = 0.00;
        $refundAmount = 0.00;

        if ($itemInfo['course']['refund_expiry_time'] > time()) {
            $refundPercent = $this->getCourseRefundPercent($order->item_id, $order->owner_id);
            $refundAmount = round(($order->amount - $serviceFee) * $refundPercent, 2);
        }

        $itemInfo['course']['refund_percent'] = $refundPercent;
        $itemInfo['course']['refund_amount'] = $refundAmount;

        return [
            'item_type' => $order->item_type,
            'item_info' => $itemInfo,
            'refund_amount' => $refundAmount,
            'service_fee' => $serviceFee,
            'service_rate' => $serviceRate,
        ];
    }

    protected function previewPackageRefund(Order $order)
    {
        $itemInfo = $order->item_info;

        $serviceFee = $this->getServiceFee($order);
        $serviceRate = $this->getServiceRate($order);

        $totalMarketPrice = 0.00;

        foreach ($itemInfo['courses'] as $course) {
            $totalMarketPrice += $course['market_price'];
        }

        $totalRefundAmount = 0.00;

        /**
         * 按照占比方式计算退款
         */
        foreach ($itemInfo['courses'] as &$course) {

            $course['cover'] = kg_cos_course_cover_url($course['cover']);

            $refundPercent = 0.00;
            $refundAmount = 0.00;

            if ($course['refund_expiry_time'] > time()) {
                $pricePercent = round($course['market_price'] / $totalMarketPrice, 4);
                $refundPercent = $this->getCourseRefundPercent($course['id'], $order->owner_id);
                $refundAmount = round(($order->amount - $serviceFee) * $pricePercent * $refundPercent, 2);
                $totalRefundAmount += $refundAmount;
            }

            $course['refund_percent'] = $refundPercent;
            $course['refund_amount'] = $refundAmount;
        }

        return [
            'item_type' => $order->item_type,
            'item_info' => $itemInfo,
            'refund_amount' => $totalRefundAmount,
            'service_fee' => $serviceFee,
            'service_rate' => $serviceRate,
        ];
    }

    protected function previewRewardRefund(Order $order)
    {
        return $this->previewOtherRefund($order);
    }

    protected function previewVipRefund(Order $order)
    {
        return $this->previewOtherRefund($order);
    }

    protected function previewTestRefund(Order $order)
    {
        return $this->previewOtherRefund($order);
    }

    protected function previewOtherRefund(Order $order)
    {
        $serviceFee = $this->getServiceFee($order);
        $serviceRate = $this->getServiceRate($order);

        $refundAmount = round($order->amount - $serviceFee, 2);

        return [
            'item_type' => $order->item_type,
            'item_info' => $order->item_info,
            'refund_amount' => $refundAmount,
            'service_fee' => $serviceFee,
            'service_rate' => $serviceRate,
        ];
    }

    protected function getServiceFee(Order $order)
    {
        $serviceRate = $this->getServiceRate($order);

        $serviceFee = round($order->amount * $serviceRate / 100, 2);

        return $serviceFee >= 0.01 ? $serviceFee : 0.00;
    }

    protected function getServiceRate(Order $order)
    {
        $orderRepo = new OrderRepository();

        $trade = $orderRepo->findLastTrade($order->id);

        $alipay = config('pay.alipay');
        $wxpay = config('pay.wechat');

        $serviceRate = 5;

        switch ($trade->channel) {
            case TradeEnums::CHANNEL_ALIPAY:
                $serviceRate = $alipay['service_rate'] ?: $serviceRate;
                break;
            case TradeEnums::CHANNEL_WXPAY:
                $serviceRate = $wxpay['service_rate'] ?: $serviceRate;
                break;
        }

        return $serviceRate;
    }

    protected function getCourseRefundPercent($courseId, $userId)
    {
        $courseRepo = new CourseRepository();

        $courseLessons = $courseRepo->findLessons($courseId);

        if ($courseLessons->count() == 0) return 1.00;

        $courseUserRepo = new CourseUserRepository();

        $courseUser = $courseUserRepo->findCourseUser($courseId, $userId);

        if (!$courseUser) return 1.00;

        $userLearnings = $courseRepo->findUserLearnings($courseId, $userId, $courseUser->plan_id);

        if ($userLearnings->count() == 0) return 1.00;

        $consumedUserLearnings = $userLearnings->filter(function ($item) {
            if ($item->consumed == 1) return $item;
        });

        if (count($consumedUserLearnings) == 0) return 1.00;

        $courseLessonIds = $courseLessons->pluck('id')->toArray();
        $consumedUserLessonIds = $consumedUserLearnings->pluck('chapter_id')->toArray();
        $consumedLessonIds = array_intersect($courseLessonIds, $consumedUserLessonIds);

        $totalCount = count($courseLessonIds);
        $consumedCount = count($consumedLessonIds);
        $refundCount = $totalCount - $consumedCount;

        return round($refundCount / $totalCount, 4);
    }


}
