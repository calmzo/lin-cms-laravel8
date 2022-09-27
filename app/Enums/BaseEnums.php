<?php
/**
 * @className: BaseEnums
 * @author: Calm
 * @date: 2022/09/27 10:39
 **/

namespace App\Enums;

use BenSampo\Enum\Enum;

class BaseEnums extends Enum
{

    const ERROR_STR = '未知';

    public static function getDesc($list = [], $key = null)
    {
        return is_null($key) ? $list : ($list[$key] ?? self::ERROR_STR);
    }

}
