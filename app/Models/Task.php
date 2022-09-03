<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Task extends BaseModel
{
    use BooleanSoftDeletes, HasFactory;

    public function order()
    {
        return $this->hasOne(Order::class,'id', 'item_id');
    }

    public function trade()
    {
        return $this->hasOne(Trade::class,'id', 'item_id');
    }

    public function refund()
    {
        return $this->hasOne(Refund::class,'id', 'item_id');
    }
}
