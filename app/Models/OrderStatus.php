<?php

namespace App\Models;

class OrderStatus extends BaseModel
{

    const UPDATED_AT = NULL;

    public function order()
    {
        return $this->hasOne(Order::class, 'id', 'order_id');
    }

}
