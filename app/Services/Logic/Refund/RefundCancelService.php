<?php

namespace App\Services\Logic\Refund;

use App\Enums\RefundEnums;
use App\Enums\TaskEnums;
use App\Repositories\RefundRepository;
use App\Services\Logic\LogicService;
use App\Services\Token\AccountLoginTokenService;
use App\Traits\RefundTrait;
use App\Validators\RefundValidator;

class RefundCancelService extends LogicService
{

    use RefundTrait;

    public function handle($sn)
    {
        $refund = $this->checkRefundBySn($sn);

        $user = AccountLoginTokenService::userModel();

        $validator = new RefundValidator();

        $validator->checkIfAllowCancel($refund);

        $validator->checkOwner($user->id, $refund->user_id);

        $refund->status = RefundEnums::STATUS_CANCELED;

        $refund->update();

        $refundRepo = new RefundRepository();

        $refundTask = $refundRepo->findLastRefundTask($refund->id);

        if ($refundTask) {
            $refundTask->status = TaskEnums::STATUS_CANCELED;
            $refundTask->update();
        }

        return $refund;
    }

}
