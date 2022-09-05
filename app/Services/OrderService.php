<?php

namespace App\Services;

use App\Enums\OrderEnums;
use App\Events\IncrOrderCountEvent;
use App\Models\Course;
use App\Models\User;
use App\Models\Order;
use App\Models\Vip;
use App\Services\Token\LoginTokenService;
use App\Traits\ClientTrait;
use App\Traits\OrderTrait;
use App\Traits\UserLimitTrait;

class OrderService
{

    use UserLimitTrait, OrderTrait, ClientTrait;

    /**
     * @var float 订单金额
     */
    protected $amount = 0.00;

    public function creatOrder($params)
    {
        $userId = LoginTokenService::userId();
        $user = User::query()->find($userId);
        IncrOrderCountEvent::dispatch($user);
        $this->checkDailyOrderLimit($user);
        $order = $this->findUserLastPendingOrder($userId, $params['item_id'], $params['item_type']);
        /**
         * 存在新鲜的未支付订单直接返回（减少订单记录）
         */
        if ($order) return $order;

        if ($params['item_type'] == OrderEnums::ITEM_COURSE) {
            $course = $this->checkCourse($params['item_id']);
            $this->checkIfBoughtCourse($user->id, $course->id);

            $this->amount = $user->vip ? $course->vip_price : $course->market_price;
            $this->checkAmount($this->amount);

            $order = $this->createCourseOrder($course, $user);

        } elseif ($params['item_type'] == OrderEnums::ITEM_VIP) {

            $vip = $this->checkVip($params['item_id']);

            $this->amount = $vip->price;

            $this->checkAmount($this->amount);

            $order = $this->createVipOrder($vip, $user);
        }

        $this->incrUserDailyOrderCount($user);

        return $order;
    }

    public function confirmOrder($itemId, $itemType)
    {
        $userId = LoginTokenService::userId();
        $user = User::query()->find($userId);

        $result = [];

        $result['item_id'] = $itemId;
        $result['item_type'] = $itemType;

        if ($itemType == OrderEnums::ITEM_COURSE) {

            $course = $this->checkCourse($itemId);

            $result['item_info']['course'] = $this->handleCourseInfo($course);

            $result['total_amount'] = $course->market_price;
            $result['pay_amount'] = $user->vip ? $course->vip_price : $course->market_price;
            $result['discount_amount'] = $result['total_amount'] - $result['pay_amount'];

        } elseif ($itemType == OrderEnums::ITEM_VIP) {

            $vip = $this->checkVip($itemId);

            $result['item_info']['vip'] = $this->handleVipInfo($vip);

            $result['total_amount'] = $vip->price;
            $result['pay_amount'] = $vip->price;
            $result['discount_amount'] = 0;

        }

        $this->checkAmount($result['pay_amount']);

        return $result;
    }

    protected function createCourseOrder(Course $course, User $user)
    {
        $itemInfo = [];

        $itemInfo['course'] = $this->handleCourseInfo($course);
        $order = new Order();

        $order->user_id = $user->id;
        $order->item_id = $course->id;
        $order->sn = $this->getOrderSn();
        $order->item_type = OrderEnums::ITEM_COURSE;
        $order->item_info = json_encode($itemInfo, JSON_UNESCAPED_SLASHES);
        $order->client_type = $this->getClientType();
        $order->client_ip = $this->getClientIp();
        $order->subject = "课程 - {$course->title}";
        $order->amount = $this->amount;
//        $order->promotion_id = $this->promotion_id;
//        $order->promotion_type = $this->promotion_type;
//        $order->promotion_info = $this->promotion_info;

        $order->save();

        return $order;
    }

    protected function handleCourseInfo(Course $course)
    {
        $studyExpiryTime = strtotime("+{$course->study_expiry} months");
        $refundExpiryTime = strtotime("+{$course->refund_expiry} days");

        return [
            'id' => $course->id,
            'title' => $course->title,
            'cover' => $course->cover,
            'model' => $course->model,
            'attrs' => $course->attrs,
            'market_price' => $course->market_price,
            'vip_price' => $course->vip_price,
            'study_expiry' => $course->study_expiry,
            'refund_expiry' => $course->refund_expiry,
            'study_expiry_time' => $studyExpiryTime,
            'refund_expiry_time' => $refundExpiryTime,
        ];
    }

    protected function createVipOrder(Vip $vip, User $user)
    {
        $baseTime = $user->vip_expiry_time > time() ? $user->vip_expiry_time : time();
        $expiryTime = strtotime("+{$vip->expiry} months", $baseTime);

        $itemInfo = [
            'vip' => [
                'id' => $vip->id,
                'title' => $vip->title,
                'price' => $vip->price,
                'expiry' => $vip->expiry,
                'expiry_time' => $expiryTime,
            ]
        ];

        $order = new Order();

        $order->user_id = $user->id;
        $order->sn = $this->getOrderSn();
        $order->item_id = $vip->id;
        $order->item_type = OrderEnums::ITEM_VIP;
        $order->item_info = json_encode($itemInfo, JSON_UNESCAPED_SLASHES);
        $order->client_type = $this->getClientType();
        $order->client_ip = $this->getClientIp();
        $order->subject = "会员 - 会员服务（{$vip->title}）";
        $order->amount = $this->amount;
//        $order->promotion_id = $this->promotion_id;
//        $order->promotion_type = $this->promotion_type;
//        $order->promotion_info = $this->promotion_info;

        $order->save();

        return $order;
    }

    /**
     * 下单vip item_info
     * @param Vip $vip
     * @return array
     */
    protected function handleVipInfo(Vip $vip)
    {
        return [
            'id' => $vip->id,
            'title' => $vip->title,
            'cover' => $vip->cover,
            'expiry' => $vip->expiry,
            'price' => $vip->price,
        ];
    }

    /**
     * 生成不重复订单sn
     * @return string|void
     */
    protected function getOrderSn()
    {
        $sn = date('YmdHis') . rand(1000, 9999);

        $order = Order::query()->where('sn', $sn)->first();
        if (!$order) return $sn;

        $this->getOrderSn();
    }

    /**
     * 增加客户订单数
     * @param User $user
     */
    protected function incrUserDailyOrderCount(User $user)
    {
        IncrOrderCountEvent::dispatch($user);
    }

}
