<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class PointGiftRedeemEnums extends Enum
{
    /**
     * 状态类型
     */
    const STATUS_PENDING = 1; // 待处理
    const STATUS_FINISHED = 2; // 已完成
    const STATUS_FAILED = 3; //　已失败
}
