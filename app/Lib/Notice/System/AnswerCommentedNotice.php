<?php

namespace App\Lib\Notice\System;

use App\Enums\NotificationEnums;
use App\Models\Answer;
use App\Models\Comment;
use App\Models\Notification;
use App\Repositories\QuestionRepository;
use App\Services\Logic\LogicService;

class AnswerCommentedNotice extends LogicService
{

    public function handle(Answer $answer, Comment $comment)
    {
        $answerSummary = kg_substr($answer->summary, 0, 32);
        $commentContent = kg_substr($comment->content, 0, 36);

        $question = $this->findQuestion($answer->question_id);

        $notification = new Notification();

        $notification->sender_id = $comment->user_id;
        $notification->receiver_id = $answer->user_id;
        $notification->event_id = $comment->id;
        $notification->event_type = NotificationEnums::TYPE_ANSWER_COMMENTED;
        $notification->event_info = [
            'question' => ['id' => $question->id, 'title' => $question->title],
            'answer' => ['id' => $answer->id, 'summary' => $answerSummary],
            'comment' => ['id' => $comment->id, 'content' => $commentContent],
        ];

        $notification->save();
    }

    protected function findQuestion($id)
    {
        $questionRepo = new QuestionRepository();

        return $questionRepo->findById($id);
    }

}
