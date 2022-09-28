<?php

namespace App\Enums;

class TagEnums extends BaseEnums
{

    /**
     * 范围类型
     */
    const SCOPE_ARTICLE = 1;
    const SCOPE_QUESTION = 2;
    const SCOPE_COURSE = 3;

    /**
     * @param null $key
     * @return array|mixed|string
     */
    public static function definition($key = null)
    {
        $list = [];
        return self::getDesc($list, $key);
    }
}

