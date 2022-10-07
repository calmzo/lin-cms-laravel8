<?php

namespace App\Lib\Notice\System;

use App\Enums\NotificationEnums;
use App\Models\Answer;
use App\Models\Notification;
use App\Models\User;
use App\Repositories\QuestionRepository;
use App\Services\Logic\LogicService;

class AnswerLikedNotice extends LogicService
{

    public function handle(Answer $answer, User $sender)
    {
        $answerSummary = kg_substr($answer->summary, 0, 36);

        $questionRepo = new QuestionRepository();

        $question = $questionRepo->findById($answer->question_id);

        $notification = new Notification();

        $notification->sender_id = $sender->id;
        $notification->receiver_id = $answer->user_id;
        $notification->event_id = $answer->id;
        $notification->event_type = NotificationEnums::TYPE_ANSWER_LIKED;
        $notification->event_info = [
            'question' => ['id' => $question->id, 'title' => $question->title],
            'answer' => ['id' => $answer->id, 'summary' => $answerSummary],
        ];

        $notification->save();
    }

}
