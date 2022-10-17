<?php

namespace App\Models;

class OrderStatus extends BaseModel
{

    public $fillable = [
        'order_id', 'status'
    ];


    public function order()
    {
        return $this->hasOne(Order::class, 'id', 'order_id');
    }

}
