<?php

namespace App\Services;

use App\Enums\CommentEnums;
use App\Models\Comment;

class CommentService
{

    public function countComments()
    {
        return Comment::query()->where('published', CommentEnums::PUBLISH_APPROVED)->count();
    }
}
