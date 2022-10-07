<?php

namespace App\Lib\Notice\System;

use App\Enums\NotificationEnums;
use App\Models\Answer;
use App\Models\Notification;
use App\Repositories\QuestionRepository;
use App\Repositories\UserRepository;
use App\Services\Logic\LogicService;

class QuestionAnsweredNotice extends LogicService
{

    public function handle(Answer $answer)
    {
        $answerSummary = kg_substr($answer->summary, 0, 36);

        $question = $this->findQuestion($answer->question_id);

        $notification = new Notification();

        $notification->sender_id = $answer->user_id;
        $notification->receiver_id = $question->user_id;
        $notification->event_id = $answer->id;
        $notification->event_type = NotificationEnums::TYPE_QUESTION_ANSWERED;
        $notification->event_info = [
            'question' => ['id' => $question->id, 'title' => $question->title],
            'answer' => ['id' => $answer->id, 'summary' => $answerSummary],
        ];

        $notification->save();
    }

    protected function findQuestion($id)
    {
        $questionRepo = new QuestionRepository();

        return $questionRepo->findById($id);
    }

    protected function findUser($id)
    {
        $userRepo = new UserRepository();

        return $userRepo->findById($id);
    }

}
