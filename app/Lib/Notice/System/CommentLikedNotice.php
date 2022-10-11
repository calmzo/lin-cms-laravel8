<?php

namespace App\Lib\Notice\System;

use App\Enums\NotificationEnums;
use App\Models\Comment;
use App\Models\Notification;
use App\Models\User;
use App\Services\Logic\LogicService;

class CommentLikedNotice extends LogicService
{

    public function handle(Comment $comment, User $sender)
    {
        $commentContent = kg_substr($comment->content, 0, 36);

        $notification = new Notification();

        $notification->sender_id = $sender->id;
        $notification->receiver_id = $comment->user_id;
        $notification->event_id = $comment->id;
        $notification->event_type = NotificationEnums::TYPE_COMMENT_LIKED;
        $notification->event_info = [
            'comment' => ['id' => $comment->id, 'content' => $commentContent],
        ];

        $notification->save();
    }

}
