<?php

namespace App\Builders;

use App\Enums\OrderEnums;
use App\Repositories\UserRepository;

class OrderListBuilder
{

    /**
     * @param array $orders
     * @return array
     */
    public function handleUsers(array $orders)
    {
        $users = $this->getUsers($orders);

        foreach ($orders as $key => $order) {
            $orders[$key]['owner'] = $users[$order['owner_id']] ?? new \stdClass();
        }

        return $orders;
    }

    /**
     * @param array $order
     * @return array|mixed
     */
    public function handleMeInfo(array $order)
    {
        $me = [
            'allow_pay' => 0,
            'allow_cancel' => 0,
            'allow_refund' => 0,
        ];

        $payStatusOk = $order['status'] == OrderEnums::STATUS_PENDING ? 1 : 0;
        $cancelStatusOk = $order['status'] == OrderEnums::STATUS_PENDING ? 1 : 0;
        $refundStatusOk = $order['status'] == OrderEnums::STATUS_FINISHED ? 1 : 0;

        if ($order['item_type'] == OrderEnums::ITEM_COURSE) {

            $course = $order['item_info']['course'];

            $courseModelOk = $course['model'] != OrderEnums::MODEL_OFFLINE;
            $refundTimeOk = $course['refund_expiry_time'] > time();

            $me['allow_refund'] = $courseModelOk && $refundStatusOk && $refundTimeOk ? 1 : 0;

        } elseif ($order['item_type'] == OrderEnums::ITEM_PACKAGE) {

            $courses = $order['item_info']['courses'];

            $refundTimeOk = false;

            foreach ($courses as $course) {
                if ($course['refund_expiry_time'] > time()) {
                    $refundTimeOk = true;
                }
            }

            $me['allow_refund'] = $refundStatusOk && $refundTimeOk ? 1 : 0;
        }

        $me['allow_pay'] = $payStatusOk;
        $me['allow_cancel'] = $cancelStatusOk;

        return $me;
    }


    /**
     * @param array $orders
     * @return array
     */
    protected function getUsers(array $orders)
    {
        $ids = array_column_unique($orders, 'user_id');

        $userRepo = new UserRepository();

        $users = $userRepo->findShallowUserByIds($ids);

        $result = [];

        foreach ($users->toArray() as $user) {
            $result[$user['id']] = $user;
        }

        return $result;
    }

}
