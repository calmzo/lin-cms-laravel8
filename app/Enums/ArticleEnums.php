<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class ArticleEnums extends Enum
{
    /**
     * 来源类型
     */
    const SOURCE_ORIGIN = 1; // 原创
    const SOURCE_REPRINT = 2; // 转载
    const SOURCE_TRANSLATE = 3; // 翻译

    /**
     * 发布状态
     */
    const PUBLISH_PENDING = 1; // 审核中
    const PUBLISH_APPROVED = 2; // 已发布
    const PUBLISH_REJECTED = 3; // 未通过


}
