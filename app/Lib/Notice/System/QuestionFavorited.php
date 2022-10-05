<?php

namespace App\Lib\Notice\System;

use App\Enums\NotificationEnums;
use App\Models\Notification;
use App\Models\Question;
use App\Models\User;
use App\Services\Logic\LogicService;

class QuestionFavorited extends LogicService
{

    public function handle(Question $question, User $sender)
    {
        $notification = new Notification();

        $notification->sender_id = $sender->id;
        $notification->receiver_id = $question->user_id;
        $notification->event_id = $question->id;
        $notification->event_type = NotificationEnums::TYPE_QUESTION_FAVORITED;
        $notification->event_info = [
            'question' => ['id' => $question->id, 'title' => $question->title],
        ];

        $notification->save();
    }

}
