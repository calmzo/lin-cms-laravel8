<?php

namespace App\Services\Logic\Point\History;

use App\Enums\PointHistoryEnums;
use App\Models\PointHistory;
use App\Models\User;
use App\Services\Logic\Point\PointHistoryService;

class AccountRegister extends PointHistoryService
{

    public function handle(User $user)
    {
        $setting = config('point');

        $pointEnabled = $setting['enabled'] ?? 0;

        if ($pointEnabled == 0) return;

        $eventRule = $setting['event_rule'];

        $eventEnabled = $eventRule['account_register']['enabled'] ?? 0;

        if ($eventEnabled == 0) return;

        $eventPoint = $eventRule['account_register']['point'] ?? 0;

        if ($eventPoint <= 0) return;

        $eventId = $user->id;
        $eventType = PointHistoryEnums::EVENT_ACCOUNT_REGISTER;
        $eventInfo = new \stdClass();

        $history =  PointHistory::query()->where('event_id', $eventId)->where('event_type', $eventType)->first();

        if ($history) return;

        $history = new PointHistory();

        $history->user_id = $user->id;
        $history->user_name = $user->name;
        $history->event_id = $user->id;
        $history->event_type = $eventType;
        $history->event_point = $eventPoint;
        $history->event_info = json_encode($eventInfo);

        $this->handlePointHistory($history);
    }

}
