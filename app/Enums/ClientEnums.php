<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class ClientEnums extends Enum
{
    const ERROR_STR = '未知';

    /**
     * 类型
     */
    const TYPE_PC = 1;
    const TYPE_H5 = 2;
    const TYPE_APP = 3;
    const TYPE_MP = 4;
    const TYPE_MP_WEIXIN = 5;
    const TYPE_MP_ALIPAY = 6;
    const TYPE_MP_BAIDU = 7;
    const TYPE_MP_TOUTIAO = 8;
    const TYPE_MP_QQ = 9;
    const TYPE_MP_360 = 10;

    public static function types($type = null)
    {
        $list = [
            self::TYPE_PC => 'PC',
            self::TYPE_H5 => 'H5',
            self::TYPE_APP => 'APP',
            self::TYPE_MP => 'MP',
            self::TYPE_MP_WEIXIN => 'MP-WEIXIN',
            self::TYPE_MP_ALIPAY => 'MP-ALIPAY',
            self::TYPE_MP_BAIDU => 'MP-BAIDU',
            self::TYPE_MP_TOUTIAO => 'MP-TOUTIAO',
            self::TYPE_MP_QQ => 'MP-QQ',
            self::TYPE_MP_360 => 'MP-360',
        ];
        return is_null($type) ? $list : ($list[$type] ?? self::ERROR_STR);
    }


}
