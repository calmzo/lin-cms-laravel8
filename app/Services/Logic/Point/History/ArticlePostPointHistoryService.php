<?php

namespace App\Services\Logic\Point\History;

use App\Enums\PointHistoryEnums;
use App\Models\Article;
use App\Models\PointHistory;
use App\Models\User;
use App\Repositories\PointHistoryRepository;
use App\Repositories\UserRepository;
use App\Services\Logic\Point\PointHistoryService;

class ArticlePostPointHistoryService extends PointHistoryService
{

    public function handle(Article $article)
    {
        $setting = config('point');

        $pointEnabled = $setting['enabled'] ?? 0;

        if ($pointEnabled == 0) return;

        $eventRule = $setting['event_rule'];

        $eventEnabled = $eventRule['article_post']['enabled'] ?? 0;

        if ($eventEnabled == 0) return;

        $eventPoint = $eventRule['article_post']['point'] ?? 0;

        if ($eventPoint <= 0) return;

        $dailyPointLimit = $eventRule['article_post']['limit'] ?? 0;

        if ($dailyPointLimit <= 0) return;

        $eventId = $article->id;

        $eventType = PointHistoryEnums::EVENT_ARTICLE_POST;
        $historyRepo = new PointHistoryRepository();
        $history = $historyRepo->findEventHistory($eventId, $eventType);

        if ($history) return;

        /**
         * @todo 使用缓存优化
         */
        $dailyPoints = $historyRepo->sumUserDailyEventPoints($article->user_id, $eventType, date('Ymd'));

        if ($dailyPoints >= $dailyPointLimit) return;

        $userRepo = new UserRepository();

        $user = $userRepo->findById($article->user_id);

        $eventInfo = [
            'article' => [
                'id' => $article->id,
                'title' => $article->title,
            ]
        ];

        $history = new PointHistory();

        $history->user_id = $user->id ?? 0;
        $history->user_name = $user->name ?? '';
        $history->event_id = $eventId;
        $history->event_type = $eventType;
        $history->event_info = $eventInfo;
        $history->event_point = $eventPoint;

        $this->handlePointHistory($history);
    }

}
