<?php

namespace App\Services\Logic\Notice\System;

use App\Enums\NotificationEnums;
use App\Models\Comment;
use App\Models\Notification;
use App\Repositories\CommentRepository;
use App\Repositories\UserRepository;
use App\Services\Logic\LogicService;

class CommentRepliedNotice extends LogicService
{

    public function handle(Comment $reply)
    {
        $replyContent = kg_substr($reply->content, 0, 36);

        $comment = $this->findComment($reply->parent_id);

        $commentContent = kg_substr($comment->content, 0, 32);

        $notification = new Notification();

        $notification->sender_id = $reply->user_id;
        $notification->receiver_id = $comment->user_id;
        $notification->event_id = $reply->id;
        $notification->event_type = NotificationEnums::TYPE_COMMENT_REPLIED;
        $notification->event_info = [
            'comment' => ['id' => $comment->id, 'content' => $commentContent],
            'reply' => ['id' => $reply->id, 'content' => $replyContent],
        ];

        $notification->save();
    }

    protected function findComment($id)
    {
        $commentRepo = new CommentRepository();

        return $commentRepo->findById($id);
    }

    protected function findUser($id)
    {
        $userRepo = new UserRepository();

        return $userRepo->findById($id);
    }

}
