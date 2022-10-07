<?php

namespace App\Services\Logic\Point\History;

use App\Enums\PointHistoryEnums;
use App\Models\Answer;
use App\Models\PointHistory;
use App\Repositories\PointHistoryRepository;
use App\Repositories\QuestionRepository;
use App\Repositories\UserRepository;
use App\Services\Logic\Point\PointHistoryService;

class AnswerAcceptedPointHistory extends PointHistoryService
{

    public function handle(Answer $answer)
    {
        $setting = config('point');

        $pointEnabled = $setting['enabled'] ?? 0;

        if ($pointEnabled == 0) return;

        $eventRule = $setting['event_rule'];

        $eventEnabled = $eventRule['answer_accepted']['enabled'] ?? 0;

        if ($eventEnabled == 0) return;

        $eventPoint = $eventRule['answer_accepted']['point'] ?? 0;

        if ($eventPoint <= 0) return;

        $eventId = $answer->id;

        $eventType = PointHistoryEnums::EVENT_ANSWER_ACCEPTED;

        $historyRepo = new PointHistoryRepository();

        $history = $historyRepo->findEventHistory($eventId, $eventType);

        if ($history) return;

        $questionRepo = new QuestionRepository();

        $question = $questionRepo->findById($answer->question_id);

        $userRepo = new UserRepository();

        $user = $userRepo->findById($answer->user_id);

        $eventInfo = [
            'question' => [
                'id' => $question->id,
                'title' => $question->title,
            ]
        ];

        $history = new PointHistory();

        $history->user_id = $user->id;
        $history->user_name = $user->name;
        $history->event_id = $eventId;
        $history->event_type = $eventType;
        $history->event_info = $eventInfo;
        $history->event_point = $eventPoint;

        $this->handlePointHistory($history);
    }

}
