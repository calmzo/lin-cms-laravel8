<?php

namespace App\Enums;

class CommentEnums extends BaseEnums
{
    /**
     * 条目类型
     */
    const ITEM_CHAPTER = 1; // 章节
    const ITEM_ARTICLE = 2; // 文章
    const ITEM_QUESTION = 3; // 问题
    const ITEM_ANSWER = 4; // 回答

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
    public static function itemTypes($key = null)
    {
        $list = [
            self::ITEM_CHAPTER => '章节',
            self::ITEM_ARTICLE => '文章',
            self::ITEM_ANSWER => '回答',
        ];
        return self::getDesc($list, $key);
    }


    /**
     * @param null $key
     * @return array|mixed|string
     */
    public static function publishTypes($key = null)
    {
        $list = [
            self::PUBLISH_PENDING => '审核中',
            self::PUBLISH_APPROVED => '已发布',
            self::PUBLISH_REJECTED => '未通过',
        ];
        return self::getDesc($list, $key);
    }


}
