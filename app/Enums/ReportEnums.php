<?php

namespace App\Enums;

class ReportEnums extends BaseEnums
{
    /**
     * 条目类型
     */
    const ITEM_USER = 100; // 用户
    const ITEM_GROUP = 101; // 小组
    const ITEM_COURSE = 102; // 课程
    const ITEM_CHAPTER = 103; // 章节
    const ITEM_CONSULT = 104; // 咨询
    const ITEM_REVIEW = 105; // 评价
    const ITEM_ARTICLE = 106; // 文章
    const ITEM_QUESTION = 107; // 问题
    const ITEM_ANSWER = 108; // 答案
    const ITEM_COMMENT = 109; // 评论

    /**
     * @param null $key
     * @return array|mixed|string
     */
    public static function itemTypes($key = null)
    {
        $list = [
            self::ITEM_USER => '用户',
            self::ITEM_GROUP => '群组',
            self::ITEM_COURSE => '课程',
            self::ITEM_CHAPTER => '章节',
            self::ITEM_CONSULT => '咨询',
            self::ITEM_REVIEW => '评价',
            self::ITEM_ARTICLE => '文章',
            self::ITEM_QUESTION => '提问',
            self::ITEM_ANSWER => '回答',
            self::ITEM_COMMENT => '评论',
        ];
        return self::getDesc($list, $key);
    }
}

