<?php

namespace App\Services\Logic\Refund;

use App\Enums\RefundEnums;
use App\Exceptions\NotFoundException;
use App\Models\Refund;
use App\Models\User;
use App\Repositories\OrderRepository;
use App\Repositories\RefundRepository;
use App\Services\Logic\LogicService;
use App\Services\Token\AccountLoginTokenService;
use App\Traits\RefundTrait;
use App\Traits\UserTrait;
use App\Utils\CodeResponse;

class RefundInfoService extends LogicService
{

    use RefundTrait;
    use UserTrait;

    public function handle($sn)
    {
        $refund = $this->checkRefundBySn($sn);

        $user = AccountLoginTokenService::userModel();

        return $this->handleRefund($refund, $user);
    }

    protected function handleRefund(Refund $refund, User $user)
    {
        $statusHistory = $this->handleStatusHistory($refund->id);
        $order = $this->handleOrderInfo($refund->order_id);
        $owner = $this->handleShallowUserInfo($refund->user_id);
        $me = $this->handleMeInfo($refund, $user);

        return [
            'sn' => $refund->sn,
            'subject' => $refund->subject,
            'amount' => $refund->amount,
            'status' => $refund->status,
            'deleted' => $refund->deleted,
            'apply_note' => $refund->apply_note,
            'review_note' => $refund->review_note,
            'create_time' => $refund->create_time,
            'update_time' => $refund->update_time,
            'status_history' => $statusHistory,
            'order' => $order,
            'owner' => $owner,
            'me' => $me,
        ];
    }

    protected function handleOrderInfo($orderId)
    {
        $orderRepo = new OrderRepository();

        $order = $orderRepo->findById($orderId);
        if (is_null($order)) {
            throw new NotFoundException(CodeResponse::NOT_FOUND_EXCEPTION, 'order.not_found');
        }
        return [
            'id' => $order->id,
            'sn' => $order->sn,
            'subject' => $order->subject,
            'amount' => $order->amount,
        ];
    }

    protected function handleStatusHistory($refundId)
    {
        $refundRepo = new RefundRepository();

        $records = $refundRepo->findStatusHistory($refundId);

        if ($records->count() == 0) {
            return [];
        }

        $result = [];

        foreach ($records as $record) {
            $result[] = [
                'status' => $record->status,
                'create_time' => $record->create_time,
            ];
        }

        return $result;
    }

    protected function handleMeInfo(Refund $refund, User $user)
    {
        $result = [
            'owned' => 0,
            'allow_cancel' => 0,
        ];

        if ($user->id == $refund->user_id) {
            $result['owned'] = 1;
        }

        $statusTypes = [
            RefundEnums::STATUS_PENDING,
            RefundEnums::STATUS_APPROVED,
        ];

        if (in_array($refund->status, $statusTypes)) {
            $result['allow_cancel'] = 1;
        }

        return $result;
    }

}
