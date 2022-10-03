<?php

namespace App\Enums;

class RefundEnums extends BaseEnums
{
    const ERROR_STR = '未知';

    /**
     * 状态类型
     */
    const STATUS_PENDING = 1; // 待处理
    const STATUS_CANCELED = 2; // 已取消
    const STATUS_APPROVED = 3; // 已审核
    const STATUS_REFUSED = 4; // 已拒绝
    const STATUS_FINISHED = 5; // 已完成
    const STATUS_FAILED = 6; // 已失败

    /**
     * @param null $key
     * @return array|mixed|string
     */
    public static function statusTypes($key = null)
    {
        $list = [
            self::STATUS_PENDING => '待处理',
            self::STATUS_CANCELED => '已取消',
            self::STATUS_APPROVED => '已审核',
            self::STATUS_REFUSED => '已拒绝',
            self::STATUS_FINISHED => '已完成',
            self::STATUS_FAILED => '已失败',
        ];
        return self::getDesc($list, $key);
    }

}
