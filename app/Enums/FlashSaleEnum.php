<?php

namespace App\Enums;

class FlashSaleEnum extends BaseEnums
{
    /**
     * 条目类型
     */
    const ITEM_COURSE = 1; // 课程
    const ITEM_PACKAGE = 2; // 套餐
    const ITEM_VIP = 3; // 会员

    /**
     * @param null $key
     * @return array|mixed|string
     */
    public static function itemTypes($key = null)
    {
        $list = [
            self::ITEM_COURSE => '课程',
            self::ITEM_PACKAGE => '套餐',
            self::ITEM_VIP => '会员',
        ];
        return self::getDesc($list, $key);
    }

    public static function schedules()
    {
        $result = [];

        foreach (range(10, 20, 2) as $hour) {
            $result[] = [
                'name' => sprintf('%02d点', $hour),
                'hour' => sprintf('%02d', $hour),
                'start_time' => sprintf('%02d:%02d:%02d', $hour, 0, 0),
                'end_time' => sprintf('%02d:%02d:%02d', $hour + 1, 59, 59)
            ];
        }

        return $result;
    }
}

