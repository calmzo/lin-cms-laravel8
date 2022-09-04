<?php

namespace App\Services;

use App\Enums\OrderEnums;
use App\Model\User;
use App\Repositories\OrderRepository;
use App\Services\Token\LoginTokenService;
use App\Traits\OrderTrait;
use App\Traits\UserLimitTrait;
use App\Validators\UserLimit;
use App\Validators\Order as OrderValidator;

class OrderService
{

    use UserLimitTrait, OrderTrait;


    //todo 下单
    public function creatOrder($params)
    {
        $userId = LoginTokenService::userId();
        $this->checkDailyOrderLimit($userId);
        $order = $this->findUserLastStatusOrder($userId, $params['item_id'], $params['item_type']);

        /**
         * 存在新鲜的未支付订单直接返回（减少订单记录）
         */
        if ($order) return $order;

        if ($params['item_type'] == OrderEnums::ITEM_COURSE) {

            $course = $orderValidator->checkCourse($post['item_id']);

            $orderValidator->checkIfBoughtCourse($user->id, $course->id);

            $this->amount = $user->vip ? $course->vip_price : $course->market_price;

            $orderValidator->checkAmount($this->amount);

            $order = $this->createCourseOrder($course, $user);

        } elseif ($post['item_type'] == OrderModel::ITEM_PACKAGE) {

            $package = $orderValidator->checkPackage($post['item_id']);

            $orderValidator->checkIfBoughtPackage($user->id, $package->id);

            $this->amount = $user->vip ? $package->vip_price : $package->market_price;

            $orderValidator->checkAmount($this->amount);

            $order = $this->createPackageOrder($package, $user);

        } elseif ($post['item_type'] == OrderModel::ITEM_REWARD) {

            list($courseId, $rewardId) = explode('-', $post['item_id']);

            $course = $orderValidator->checkCourse($courseId);
            $reward = $orderValidator->checkReward($rewardId);

            $this->amount = $reward->price;

            $orderValidator->checkAmount($this->amount);

            $order = $this->createRewardOrder($course, $reward, $user);

        } elseif ($post['item_type'] == OrderModel::ITEM_VIP) {

            $vip = $orderValidator->checkVip($post['item_id']);

            $this->amount = $vip->price;

            $orderValidator->checkAmount($this->amount);

            $order = $this->createVipOrder($vip, $user);
        }

        $this->incrUserDailyOrderCount($user);

        return $order;
    }

    protected function incrUserDailyOrderCount(User $user)
    {
        $this->eventsManager->fire('UserDailyCounter:incrOrderCount', $this, $user);
    }

}
