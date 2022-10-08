<?php

namespace App\Services\Logic\Notice\System;

use App\Enums\NotificationEnums;
use App\Models\Comment;
use App\Models\Notification;
use App\Models\Question;
use App\Services\Logic\LogicService;

class QuestionCommentedNotice extends LogicService
{

    public function handle(Question $question, Comment $comment)
    {
        $commentContent = kg_substr($comment->content, 0, 36);

        $notification = new Notification();

        $notification->sender_id = $comment->user_id;
        $notification->receiver_id = $question->user_id;
        $notification->event_id = $comment->id;
        $notification->event_type = NotificationEnums::TYPE_QUESTION_COMMENTED;
        $notification->event_info = [
            'question' => ['id' => $question->id, 'title' => $question->title],
            'comment' => ['id' => $comment->id, 'content' => $commentContent],
        ];

        $notification->save();
    }

}
