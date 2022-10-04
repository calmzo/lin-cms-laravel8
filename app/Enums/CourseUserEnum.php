<?php

namespace App\Enums;

class CourseUserEnum extends BaseEnums
{
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

    /**
     * @param null $key
     * @return array|mixed|string
     */
    public static function roleTypes($key = null)
    {
        $list = [
            self::ROLE_STUDENT => '学员',
            self::ROLE_TEACHER => '讲师',
        ];
        return self::getDesc($list, $key);
    }

    /**
     * @param null $key
     * @return array|mixed|string
     */
    public static function sourceTypes($key = null)
    {
        $list = [
            self::SOURCE_FREE => '免费',
            self::SOURCE_CHARGE => '付费',
            self::SOURCE_VIP => '会员',
            self::SOURCE_IMPORT => '导入',
            self::SOURCE_POINT_REDEEM => '积分兑换',
            self::SOURCE_LUCKY_REDEEM => '抽奖兑换',
        ];
        return self::getDesc($list, $key);
    }
}

