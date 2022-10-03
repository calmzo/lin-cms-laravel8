<?php

namespace App\Enums;

class OrderEnums extends BaseEnums
{
    /**
     * 模型
     */
    const MODEL_VOD = 1; // 点播
    const MODEL_LIVE = 2; // 直播
    const MODEL_READ = 3; // 图文
    const MODEL_OFFLINE = 4; // 面授

    /**
     * 级别
     */
    const LEVEL_ENTRY = 1; // 入门
    const LEVEL_JUNIOR = 2; // 初级
    const LEVEL_MEDIUM = 3; // 中级
    const LEVEL_SENIOR = 4; // 高级


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
