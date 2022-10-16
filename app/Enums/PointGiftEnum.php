<?php

namespace App\Enums;

class PointGiftEnum extends BaseEnums
{

    /**
     * 礼物类型
     */
    const TYPE_COURSE = 1; // 课程
    const TYPE_GOODS = 2; // 商品
    const TYPE_VIP = 3; // 会员

    /**
     * @param null $key
     * @return array|mixed|string
     */
    public static function types($key = null)
    {
        $list = [
            self::TYPE_COURSE => '课程',
            self::TYPE_GOODS => '商品',
            self::TYPE_VIP => '会员',
        ];
        return self::getDesc($list, $key);
    }
}

