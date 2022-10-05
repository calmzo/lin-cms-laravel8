<?php

namespace App\Models;

class Refund extends BaseModel
{
    public $fillable = ['amount', 'subject', 'user_id', 'order_id', 'trade_id', 'apply_note', 'review_note', 'status'];
}
