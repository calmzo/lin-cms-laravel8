<?php

namespace App\Services\Logic\Point\History;

use App\Enums\PointHistoryEnums;
use App\Models\Comment;
use App\Models\PointHistory;
use App\Repositories\PointHistoryRepository;
use App\Repositories\UserRepository;
use App\Services\Logic\Point\PointHistoryService;

class CommentPostPointHistory extends PointHistoryService
{

    public function handle(Comment $comment)
    {
        $setting = config('point');

        $pointEnabled = $setting['enabled'] ?? 0;

        if ($pointEnabled == 0) return;

        $eventRule = $setting['event_rule'];

        $eventEnabled = $eventRule['comment_post']['enabled'] ?? 0;

        if ($eventEnabled == 0) return;

        $eventPoint = $eventRule['comment_post']['point'] ?? 0;

        if ($eventPoint <= 0) return;

        $dailyPointLimit = $eventRule['comment_post']['limit'] ?? 0;

        if ($dailyPointLimit <= 0) return;

        $eventId = $comment->id;

        $eventType = PointHistoryEnums::EVENT_COMMENT_POST;

        $historyRepo = new PointHistoryRepository();

        $history = $historyRepo->findEventHistory($eventId, $eventType);

        if ($history) return;

        /**
         * @todo 使用缓存优化
         */
        $dailyPoints = $historyRepo->sumUserDailyEventPoints($comment->user_id, $eventType, date('Ymd'));

        if ($dailyPoints >= $dailyPointLimit) return;

        $userRepo = new UserRepository();

        $user = $userRepo->findById($comment->user_id);

        $commentContent = kg_substr($comment->content, 0, 32);

        $eventInfo = [
            'comment' => [
                'id' => $comment->id,
                'content' => $commentContent,
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
