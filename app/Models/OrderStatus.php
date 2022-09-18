<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrderStatus extends BaseModel
{
    use HasFactory;

    const UPDATED_AT = NULL;

    public function order()
    {
        return $this->hasOne(Order::class, 'id', 'order_id');
    }

}
