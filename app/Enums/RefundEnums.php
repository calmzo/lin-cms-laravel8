<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class RefundEnums extends Enum
{
    const ERROR_STR = '未知';

    /**
     * 状态类型
     */
    const STATUS_PENDING = 1; // 待处理
    const STATUS_CANCELED = 2; // 已取消
    const STATUS_APPROVED = 3; // 已审核
    const STATUS_REFUSED = 4; // 已拒绝
    const STATUS_FINISHED = 5; // 已完成
    const STATUS_FAILED = 6; // 已失败

}
