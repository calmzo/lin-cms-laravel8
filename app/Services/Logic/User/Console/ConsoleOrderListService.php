<?php

namespace App\Services\Logic\User\Console;

use App\Builders\OrderListBuilder;
use App\Repositories\OrderRepository;
use App\Services\Logic\LogicService;
use App\Services\Token\AccountLoginTokenService;
use App\Traits\UserTrait;
use App\Lib\Validators\OrderValidator;

class ConsoleOrderListService extends LogicService
{

    use UserTrait;

    public function handle()
    {
        $uid = AccountLoginTokenService::userId();
        $params['user_id'] = $uid;
        $page = $params['page'] ?? 1;
        $sort = $params['sort'] ?? 'latest';
        $limit = $params['limit'] ?? 15;

        $validator = new OrderValidator();

        if (!empty($params['status'])) {
            $params['status'] = $validator->checkStatus($params['status']);
        }

        $orderRepo = new OrderRepository();

        $pager = $orderRepo->paginate($params, $sort, $page, $limit);

        return $this->handleOrders($pager);
    }

    public function handleOrders($paginate)
    {
        if ($paginate->total() == 0) {
            return $paginate;
        }

        $builder = new OrderListBuilder();

        $orders = collect($paginate->items())->toArray();

        $items = [];

        foreach ($orders as $order) {

            $me = $builder->handleMeInfo($order);

            $items[] = [
                'sn' => $order['sn'],
                'subject' => $order['subject'],
                'amount' => (float)$order['amount'],
                'status' => $order['status'],
                'item_id' => $order['item_id'],
                'item_type' => $order['item_type'],
                'item_info' => $order['item_info'],
                'promotion_id' => $order['promotion_id'],
                'promotion_type' => $order['promotion_type'],
                'promotion_info' => $order['promotion_info'],
                'create_time' => $order['create_time'],
                'update_time' => $order['update_time'],
                'me' => $me,
            ];
        }
        $paginate = $this->newPaginator($paginate, $items);

        return $paginate;
    }

}
