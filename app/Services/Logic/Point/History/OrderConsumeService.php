<?php

namespace App\Services\Logic\Point\History;

use App\Enums\PointHistoryEnums;
use App\Models\Order;
use App\Models\PointHistory;
use App\Models\User;
use App\Services\Logic\Point\PointHistoryService;

class OrderConsumeService extends PointHistoryService
{

    public function handle(Order $order)
    {
        $setting = config('point');

        $pointEnabled = $setting['enabled'] ?? 0;
        if ($pointEnabled == 0) return;

        $consumeRule = $setting['consume_rule'] ?? [];

        $ruleEnabled = $consumeRule['enabled'] ?? 0;

        if ($ruleEnabled == 0) return;

        $ruleRate = $consumeRule['rate'] ?? 0;

        if ($ruleRate <= 0) return;

        $eventId = $order->id;
        $eventType = PointHistoryEnums::EVENT_ORDER_CONSUME;
        $eventPoint = $ruleRate * $order->amount;
        $pointHistoryService = new PointHistoryService();
        $history = $pointHistoryService->findEventHistory($eventId, $eventType);
        if ($history) return;

        $user = User::query()->find($order->user_id);

        $eventInfo = [
            'order' => [
                'sn' => $order->sn,
                'subject' => $order->subject,
                'amount' => $order->amount,
            ]
        ];
        $history = new PointHistory();

        $history->user_id = $user->id;
        $history->user_name = $user->name;
        $history->event_id = $eventId;
        $history->event_type = $eventType;
        $history->event_point = $eventPoint;
        $history->event_info = json_encode($eventInfo);

        $this->handlePointHistory($history);
    }
}
