<?php

namespace App\Services\Logic\Point\History;

use App\Enums\PointHistoryEnums;
use App\Models\PointHistory;
use App\Models\User;
use App\Repositories\PointHistoryRepository;
use App\Services\Logic\Point\PointHistoryService;

class SiteVisitPointHistory extends PointHistoryService
{

    public function handle(User $user)
    {
        $setting = config('point');

        $pointEnabled = $setting['enabled'] ?? 0;

        if ($pointEnabled == 0) return;

        $eventRule = $setting['event_rule'];

        $eventEnabled = $eventRule['site_visit']['enabled'] ?? 0;

        if ($eventEnabled == 0) return;

        $eventPoint = $eventRule['site_visit']['point'] ?? 0;

        if ($eventPoint <= 0) return;

        $eventId = $user->id;
        $eventType = PointHistoryEnums::EVENT_SITE_VISIT;
        $eventInfo = new \stdClass();

        $historyRepo = new PointHistoryRepository();

        $history = $historyRepo->findDailyEventHistory($eventId, $eventType, date('Ymd'));

        if ($history) return;

        $history = new PointHistory();

        $history->user_id = $user->id;
        $history->user_name = $user->name;
        $history->event_id = $eventId;
        $history->event_type = $eventType;
        $history->event_point = $eventPoint;
        $history->event_info = $eventInfo;

        $this->handlePointHistory($history);
    }

}
