<?php

namespace App\Models;

class Refund extends BaseModel
{
    public $fillable = ['amount', 'subject', 'user_id', 'order_id', 'trade_id', 'apply_note', 'review_note', 'status'];

    /**
     * 模型的 "booted" 方法
     *
     * @return void
     */
    protected static function booted()
    {
        //处理 Refund「saved」事件
        static::saved(function ($refund) {
            //更新订单状态
            RefundStatus::query()->create(['refund_id' => $refund->getAttribute('id'), 'status' => $refund->getAttribute('status')]);

        });

        //处理 Refund「creating」事件
        static::creating(function ($refund) {
            $refund->sn = self::getOrderSn();
        });
    }

    /**
     * 生成不重复订单sn
     * @return string|void
     */
    protected static function getOrderSn()
    {
        $sn = date('YmdHis') . rand(1000, 9999);

        $order = Refund::query()->where('sn', $sn)->first();
        if (!$order) {
            return $sn;
        }

        self::getOrderSn();
    }


    public function refundStatus()
    {
        return $this->belongsTo(RefundStatus::class, 'id', 'refund_id');
    }
}
