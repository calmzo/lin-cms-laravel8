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


}
