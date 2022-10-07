<?php

namespace App\Services\Logic\Point\History;

use App\Enums\PointHistoryEnums;
use App\Models\Answer;
use App\Models\PointHistory;
use App\Repositories\PointHistoryRepository;
use App\Repositories\QuestionRepository;
use App\Repositories\UserRepository;
use App\Services\Logic\Point\PointHistoryService;

class AnswerPostPointHistory extends PointHistoryService
{

    public function handle(Answer $answer)
    {
        $setting = config('point');

        $pointEnabled = $setting['enabled'] ?? 0;

        if ($pointEnabled == 0) return;

        $eventRule = $setting['event_rule'];

        $eventEnabled = $eventRule['answer_post']['enabled'] ?? 0;

        if ($eventEnabled == 0) return;

        $eventPoint = $eventRule['answer_post']['point'] ?? 0;

        if ($eventPoint <= 0) return;

        $dailyPointLimit = $eventRule['answer_post']['limit'] ?? 0;

        if ($dailyPointLimit <= 0) return;

        $eventId = $answer->id;

        $eventType = PointHistoryEnums::EVENT_ANSWER_POST;

        $historyRepo = new PointHistoryRepository();

        $history = $historyRepo->findEventHistory($eventId, $eventType);

        if ($history) return;

        /**
         * @todo 使用缓存优化
         */
        $dailyPoints = $historyRepo->sumUserDailyEventPoints($answer->user_id, $eventType, date('Ymd'));

        if ($dailyPoints >= $dailyPointLimit) return;

        $questionRepo = new QuestionRepository();

        $question = $questionRepo->findById($answer->question_id);

        $userRepo = new UserRepository();

        $user = $userRepo->findById($answer->user_id);

        $answerSummary = kg_substr($answer->summary, 0, 32);

        $eventInfo = [
            'question' => [
                'id' => $question->id,
                'title' => $question->title,
            ],
            'answer' => [
                'id' => $answer->id,
                'summary' => $answerSummary,
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
