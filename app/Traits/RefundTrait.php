<?php

namespace App\Traits;

use App\Enums\RefundEnums;
use App\Exceptions\BadRequestException;
use App\Models\Refund;
use App\Utils\CodeResponse;
use App\Validators\RefundValidator;

trait RefundTrait
{

    /**
     * 校验退款
     * @param $id
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model
     * @throws BadRequestException
     */
    public function checkRefund($id)
    {
        $trade = Refund::query()->find($id);
        if (!$trade) {
            throw new BadRequestException(CodeResponse::NOT_FOUND_EXCEPTION, '退款不存在');
        }

        return $trade;
    }

    public function checkRefundBySn($sn)
    {
        $validator = new RefundValidator();

        return $validator->checkRefundBySn($sn);
    }

    /**
     * 校验审核状态
     * @param Refund $refund
     * @throws BadRequestException
     */
    public function checkIfAllowReview(Refund $refund)
    {
        if ($refund->status != RefundEnums::STATUS_PENDING) {
            throw new BadRequestException(CodeResponse::NOT_FOUND_EXCEPTION, '当前不允许审核退款');
        }
    }

    /**
     * 校验审核状态类型
     * @param $status
     * @return mixed
     * @throws BadRequestException
     */
    public function checkReviewStatus($status)
    {
        $list = [
            RefundEnums::STATUS_APPROVED,
            RefundEnums::STATUS_REFUSED,
        ];

        if (!in_array($status, $list)) {
            throw new BadRequestException(CodeResponse::NOT_FOUND_EXCEPTION, '无效的状态类型');

        }

        return $status;
    }

}
