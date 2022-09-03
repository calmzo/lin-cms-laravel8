<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Refund extends BaseModel
{
    use BooleanSoftDeletes, HasFactory;
    public $fillable = ['amount', 'subject', 'user_id', 'order_id', 'trade_id', 'apply_note', 'review_note', 'status'];
}
