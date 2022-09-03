<?php

namespace App\Services\Admin;

use App\Enums\RefundEnums;
use App\Enums\TaskEnums;
use App\Models\Task;
use App\Traits\RefundTrait;


class RefundService
{
    use RefundTrait;

    public function reviewRefund($id, $params)
    {
        $status = $params['review_status'];
        $reviewNote = $params['review_note'];
        $refund = $this->checkRefund($id);

        $this->checkIfAllowReview($refund);
        $data = [];
        $data['status'] = $this->checkReviewStatus($status);
        $data['review_note'] = $reviewNote;

        $refund->update($data);

        if ($refund->status == RefundEnums::STATUS_APPROVED) {

            $task = new Task();

            $itemInfo = [
                'refund' => ['id' => $refund->id],
            ];

            $task->item_id = $refund->id;
            $task->item_type = TaskEnums::TYPE_REFUND;
            $task->item_info = json_encode($itemInfo, JSON_UNESCAPED_SLASHES);
            $task->priority = TaskEnums::PRIORITY_HIGH;
            $task->status = TaskEnums::STATUS_PENDING;

            $task->save();
        }

        return $refund;
    }



}
