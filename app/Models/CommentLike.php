<?php

namespace App\Models;

class CommentLike extends BaseModel
{
    public $fillable = [
        'comment_id', 'user_id'
    ];
}
