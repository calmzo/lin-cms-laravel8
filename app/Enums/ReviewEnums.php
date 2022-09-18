<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class ReviewEnums extends Enum
{
    /**
     * 发布状态
     */
    const PUBLISH_PENDING = 1; // 审核中
    const PUBLISH_APPROVED = 2; // 已发布
    const PUBLISH_REJECTED = 3; // 未通过


}
