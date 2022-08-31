<?php

namespace App\Enums;

class TradeEnums
{
    const ERROR_STR = '未知';

    /**
     * 平台类型
     */
    const CHANNEL_ALIPAY = 1; // 支付宝
    const CHANNEL_WXPAY = 2; // 微信

    /**
     * 状态类型
     */
    const STATUS_PENDING = 1; // 待支付
    const STATUS_FINISHED = 2; // 已完成
    const STATUS_CLOSED = 3; // 已关闭
    const STATUS_REFUNDED = 4; // 已退款

    public static function channelTypes($type = null)
    {
        $list =  [
            self::CHANNEL_ALIPAY => '支付宝',
            self::CHANNEL_WXPAY => '微信',
        ];
        return is_null($type) ? $list : ($list[$type] ?? self::ERROR_STR);

    }

}
