<?php

namespace App\Models;

class Order extends BaseModel
{
    public $fillable = [
        'user_id', 'item_id', 'item_type', 'item_info', 'amount', 'subject', 'sn', 'promotion_id', 'promotion_type',
        'promotion_info', 'client_type', 'client_ip', 'status'
    ];


    /**
     * 模型的 "booted" 方法
     *
     * @return void
     */
    protected static function booted()
    {
        //处理 Order「saved」事件
        static::saved(function ($order) {
            //更新订单状态
            if ($order->isDirty('status')) {
                $orderStatus = new OrderStatus();
                $orderStatus->order_id = $order->getAttribute('id');
                $orderStatus->status = $order->getAttribute('status');
                $orderStatus->save();
            }
        });

        //处理 Order「created」事件
        static::created(function ($order) {
            //更新订单状态
            $orderStatus = new OrderStatus();
            $orderStatus->order_id = $order->getAttribute('id');
            $orderStatus->save();
        });

        //处理 Order「creating」事件
        static::creating(function ($order) {
            $order->sn = self::getOrderSn();
        });
    }

    public function setItemInfoAttribute($value)
    {
        if (is_array($value) || is_object($value)) {
            $value = json_encode($value, JSON_UNESCAPED_UNICODE);
        }
        $this->attributes['item_info'] = $value;
    }

    public function getItemInfoAttribute($value)
    {
        if (is_string($value)) {
            $value = json_decode($value, true);
        }
        return $value;
    }


    /**
     * 生成不重复订单sn
     * @return string|void
     */
    protected static function getOrderSn()
    {
        $sn = date('YmdHis') . rand(1000, 9999);

        $order = Order::query()->where('sn', $sn)->first();
        if (!$order) {
            return $sn;
        }

        self::getOrderSn();
    }

    public function orderStatus()
    {
        return $this->belongsTo(OrderStatus::class, 'id', 'order_id');
    }
}
