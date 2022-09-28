<?php

namespace App\Services\Logic\Point\History;

use App\Enums\PointHistoryEnums;
use App\Models\Article;
use App\Models\ArticleLike;
use App\Models\PointHistory;
use App\Models\User;
use App\Services\Logic\Point\PointHistoryService;

class ArticleLikedService extends PointHistoryService
{

    public function handle(ArticleLike $articleLike)
    {
        $setting = config('point');

        $pointEnabled = $setting['enabled'] ?? 0;

        if ($pointEnabled == 0) return;

        $eventRule = $setting['event_rule'];

        $eventEnabled = $eventRule['article_liked']['enabled'] ?? 0;

        if ($eventEnabled == 0) return;

        $eventPoint = $eventRule['article_liked']['point'] ?? 0;

        if ($eventPoint <= 0) return;

        $dailyPointLimit = $eventRule['article_liked']['limit'] ?? 0;

        if ($dailyPointLimit <= 0) return;

        $eventId = $articleLike->id;

        $eventType = PointHistoryEnums::EVENT_ARTICLE_LIKED;

        $historyService = new PointHistoryService();

        $history = $historyService->findEventHistory($eventId, $eventType);

        if ($history) return;

        $article = Article::query()->find($articleLike->article_id);

        /**
         * @todo 使用缓存优化
         */
        $dailyPoints = $historyService->sumUserDailyEventPoints($article->user_id, $eventType, date('Ymd'));

        if ($dailyPoints >= $dailyPointLimit) return;

        $user = User::query()->find($article->user_id);

        $eventInfo = [
            'article' => [
                'id' => $article->id,
                'title' => $article->title,
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