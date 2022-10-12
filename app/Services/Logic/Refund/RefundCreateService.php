<?php

namespace App\Services\Logic\Refund;

use App\Enums\RefundEnums;
use App\Enums\TaskEnums;
use App\Models\Refund;
use App\Models\Task;
use App\Repositories\OrderRepository;
use App\Services\Logic\LogicService;
use App\Services\RefundService;
use App\Services\Token\AccountLoginTokenService;
use App\Traits\OrderTrait;
use App\Validators\OrderValidator;
use App\Validators\RefundValidator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RefundCreateService extends LogicService
{

    use OrderTrait;

    public function handle()
    {
        $logger = Log::channel('refund');

        $post = $this->getRequest()->post();
        $order = $this->checkOrderBySn($post['order_sn']);

        $user = AccountLoginTokenService::userModel();

        $orderRepo = new OrderRepository();

        $trade = $orderRepo->findLastTrade($order->id);

        $validator = new OrderValidator();

        $validator->checkOwner($user->id, $order->user_id);

        $validator->checkIfAllowRefund($order);

        $refundService = new RefundService();

        $preview = $refundService->preview($order);

        $refundAmount = $preview['refund_amount'];

        $validator = new RefundValidator();

        $applyNote = $validator->checkApplyNote($post['apply_note']);

        $validator->checkAmount($order->amount, $refundAmount);

        try {

            DB::beginTransaction();
            $refundData = [
                'subject' => $order->subject,
                'amount' => $refundAmount,
                'apply_note' => $applyNote,
                'order_id' => $order->id,
                'trade_id' => $trade->id,
                'user_id' => $user->id,
                'status' => RefundEnums::STATUS_APPROVED,
                'review_note' => '退款周期内无条件审批',
            ];
            $refund = Refund::query()->create($refundData);

            if (!$refund) {
                throw new \RuntimeException('Create Refund Failed');
            }

            $itemInfo = [
                'refund' => ['id' => $refund->id],
            ];
            $taskData = [
                'item_id' => $refund->id,
                'item_type' => TaskEnums::TYPE_REFUND,
                'item_info' => $itemInfo,
                'priority' => TaskEnums::PRIORITY_MIDDLE,
                'status' => TaskEnums::STATUS_PENDING,
            ];
            $task = Task::query()->create($taskData);

            if (!$task) {
                throw new \RuntimeException('Create Refund Task Failed');
            }

            DB::commit();

            return $refund;

        } catch (\Exception $e) {

            DB::rollBack();

            $logger->error('Create Refund Exception ' . kg_json_encode([
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'message' => $e->getMessage(),
                ]));

            throw new \RuntimeException('sys.trans_rollback');
        }
    }

}
