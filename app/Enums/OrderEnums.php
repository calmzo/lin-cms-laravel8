<?php

namespace App\Enums;

class OrderEnums
{
    /**
     * 条目类型
     */
    const ITEM_COURSE = 1; // 课程
    const ITEM_PACKAGE = 2; // 套餐
    const ITEM_REWARD = 3; // 赞赏
    const ITEM_VIP = 4; // 会员
    const ITEM_TEST = 99; // 测试

    public static function itemTypes()
    {
        return [
            self::ITEM_COURSE => '课程',
            self::ITEM_PACKAGE => '套餐',
            self::ITEM_REWARD => '赞赏',
            self::ITEM_VIP => '会员',
            self::ITEM_TEST => '测试',
        ];
    }

    const PROMOTION_FLASH_SALE = 1; // 限时秒杀
    const PROMOTION_DISCOUNT = 2; // 限时折扣

    /**
     * 状态类型
     */
    const STATUS_PENDING = 1; // 待支付
    const STATUS_DELIVERING = 2; // 发货中
    const STATUS_FINISHED = 3; // 已完成
    const STATUS_CLOSED = 4; // 已关闭
    const STATUS_REFUNDED = 5; // 已退款


}
