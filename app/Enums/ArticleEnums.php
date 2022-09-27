<?php

namespace App\Enums;

class ArticleEnums extends BaseEnums
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


    /**
     * @param null $key
     * @return array|mixed|string
     */
    public static function sourceTypes($key = null)
    {
        $list = [
            self::SOURCE_ORIGIN => '原创',
            self::SOURCE_REPRINT => '转载',
            self::SOURCE_TRANSLATE => '翻译',
        ];
        return self::getDesc($list, $key);
    }

}
