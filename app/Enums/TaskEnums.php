<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class TaskEnums extends Enum
{
    const ERROR_STR = '未知';

    /**
     * 任务类型
     */
    const TYPE_DELIVER = 1; // 发货
    const TYPE_REFUND = 2; // 退款
    const TYPE_POINT_GIFT_DELIVER = 3; // 积分礼品派发
    const TYPE_LUCKY_GIFT_DELIVER = 4; // 抽奖礼品派发

    /**
     * 针对外部用户
     */
    const TYPE_NOTICE_ACCOUNT_LOGIN = 11; // 帐号登录通知
    const TYPE_NOTICE_LIVE_BEGIN = 12; // 直播学员通知
    const TYPE_NOTICE_ORDER_FINISH = 13; // 订单完成通知
    const TYPE_NOTICE_REFUND_FINISH = 14; // 退款完成通知
    const TYPE_NOTICE_CONSULT_REPLY = 15; // 咨询回复通知
    const TYPE_NOTICE_POINT_GOODS_DELIVER = 16; // 积分商品发货通知
    const TYPE_NOTICE_LUCKY_GOODS_DELIVER = 17; // 中奖商品发货通知

    /**
     * 针对内部人员
     */
    const TYPE_STAFF_NOTICE_CONSULT_CREATE = 31; // 咨询创建通知
    const TYPE_STAFF_NOTICE_TEACHER_LIVE = 32; // 直播讲师通知
    const TYPE_STAFF_NOTICE_SERVER_MONITOR = 33; // 服务监控通知
    const TYPE_STAFF_NOTICE_CUSTOM_SERVICE = 34; // 客服消息通知
    const TYPE_STAFF_NOTICE_POINT_GIFT_REDEEM = 35; // 积分兑换通知
    const TYPE_STAFF_NOTICE_LUCKY_GIFT_REDEEM = 36; // 抽奖兑换通知

    /**
     * 优先级
     */
    const PRIORITY_HIGH = 10; // 高
    const PRIORITY_MIDDLE = 20; // 中
    const PRIORITY_LOW = 30; // 低

    /**
     * 状态类型
     */
    const STATUS_PENDING = 1; // 待定
    const STATUS_FINISHED = 2; // 完成
    const STATUS_CANCELED = 3; // 取消
    const STATUS_FAILED = 4; // 失败

}
