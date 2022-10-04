<?php

namespace App\Services\Logic\User\Console;

use App\Builders\RefundListBuilder;
use App\Repositories\RefundRepository;
use App\Services\Logic\LogicService;
use App\Services\Token\AccountLoginTokenService;
use App\Traits\UserTrait;
use App\Validators\RefundValidator;

class ConsoleRefundListService extends LogicService
{

    use UserTrait;

    public function handle()
    {
        $uid = AccountLoginTokenService::userId();
        $params['user_id'] = $uid;
        $page = $params['page'] ?? 1;
        $sort = $params['sort'] ?? 'latest';
        $limit = $params['limit'] ?? 15;
        $validator = new RefundValidator();
        if (!empty($params['status'])) {
            $params['status'] = $validator->checkStatus($params['status']);
        }

        $refundRepo = new RefundRepository();

        $pager = $refundRepo->paginate($params, $sort, $page, $limit);

        return $this->handleRefunds($pager);
    }

    protected function handleRefunds($paginate)
    {
        if ($paginate->total() == 0) {
            return $paginate;
        }

        $builder = new RefundListBuilder();

        $refunds = collect($paginate->items())->toArray();

        $orders = $builder->getOrders($refunds);

        $items = [];

        foreach ($refunds as $refund) {

            $order = $orders[$refund['order_id']] ?? new \stdClass();

            $me = $builder->handleMeInfo($refund);

            $items[] = [
                'sn' => $refund['sn'],
                'subject' => $refund['subject'],
                'amount' => (float)$refund['amount'],
                'status' => $refund['status'],
                'apply_note' => $refund['apply_note'],
                'review_note' => $refund['review_note'],
                'create_time' => $refund['create_time'],
                'update_time' => $refund['update_time'],
                'order' => $order,
                'me' => $me,
            ];
        }

        $paginate = $this->newPaginator($paginate, $items);

        return $paginate;
    }

}
