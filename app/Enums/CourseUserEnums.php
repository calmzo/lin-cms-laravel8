<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class CourseUserEnums extends Enum
{
    const ERROR_STR = '未知';

    /**
     * 角色类型
     */
    const ROLE_STUDENT = 1; // 学员
    const ROLE_TEACHER = 2; // 讲师

    /**
     * 来源类型
     */
    const SOURCE_FREE = 1; // 免费
    const SOURCE_CHARGE = 2; // 付费
    const SOURCE_VIP = 3; // 会员（畅学）
    const SOURCE_IMPORT = 4; // 导入
    const SOURCE_POINT_REDEEM = 5; // 积分兑换
    const SOURCE_LUCKY_REDEEM = 6; // 抽奖兑换


}
