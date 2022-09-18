<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class PointHistoryEnums extends Enum
{
    const ERROR_STR = '未知';

    /**
     * 事件类型
     */
    const EVENT_ORDER_CONSUME = 1; // 订单消费
    const EVENT_POINT_GIFT_REDEEM = 2; // 积分礼品兑换
    const EVENT_POINT_GIFT_REFUND = 3; // 积分礼品退款
    const EVENT_ACCOUNT_REGISTER = 4; // 帐号注册
    const EVENT_SITE_VISIT = 5; // 站点访问
    const EVENT_CHAPTER_STUDY = 6; // 课时学习
    const EVENT_COURSE_REVIEW = 7; // 课程评价
    const EVENT_IM_DISCUSS = 8; // 微聊讨论
    const EVENT_COMMENT_POST = 9; // 发布评论
    const EVENT_ARTICLE_POST = 10; // 发布文章
    const EVENT_QUESTION_POST = 11; // 发布问题
    const EVENT_ANSWER_POST = 12; // 发布回答
    const EVENT_ARTICLE_LIKED = 13; // 文章被点赞
    const EVENT_QUESTION_LIKED = 14; // 提问被点赞
    const EVENT_ANSWER_LIKED = 15; // 回答被点赞
    const EVENT_ANSWER_ACCEPTED = 16; // 回答被采纳


}
