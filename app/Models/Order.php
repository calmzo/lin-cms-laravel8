<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends BaseModel
{
    use BooleanSoftDeletes, HasFactory;



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
    }
}
