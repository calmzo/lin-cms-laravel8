<?php

namespace App\Builders;

use App\Enums\RefundEnums;
use App\Repositories\OrderRepository;
use App\Repositories\UserRepository;

class RefundListBuilder
{

    public function handleOrders(array $trades)
    {
        $orders = $this->getOrders($trades);

        foreach ($trades as $key => $trade) {
            $trades[$key]['order'] = $orders[$trade['order_id']] ?? new \stdClass();
        }

        return $trades;
    }

    public function handleUsers(array $refunds)
    {
        $users = $this->getUsers($refunds);

        foreach ($refunds as $key => $refund) {
            $refunds[$key]['owner'] = $users[$refund['user_id']] ?? new \stdClass();
        }

        return $refunds;
    }

    public function handleMeInfo(array $refund)
    {
        $me = [
            'allow_cancel' => 0,
        ];

        $statusTypes = [
            RefundEnums::STATUS_PENDING,
            RefundEnums::STATUS_APPROVED,
        ];

        if (in_array($refund['status'], $statusTypes)) {
            $me['allow_cancel'] = 1;
        }

        return $me;
    }

    public function getOrders(array $trades)
    {
        $ids = array_column_unique($trades, 'order_id');

        $orderRepo = new OrderRepository();

        $orders = $orderRepo->findByIds($ids, ['id', 'sn', 'subject', 'amount']);

        $result = [];

        foreach ($orders->toArray() as $order) {
            $result[$order['id']] = $order;
        }

        return $result;
    }

    public function getUsers(array $refunds)
    {
        $ids = array_column_unique($refunds, 'user_id');

        $userRepo = new UserRepository();

        $users = $userRepo->findShallowUserByIds($ids);

//        $baseUrl = kg_cos_url();

        $result = [];

        foreach ($users->toArray() as $user) {
//            $user['avatar'] = $baseUrl . $user['avatar'];
            $result[$user['id']] = $user;
        }

        return $result;
    }

}
