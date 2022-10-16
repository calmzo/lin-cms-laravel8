<?php

namespace App\Services\Logic\Point\History;

use App\Enums\PointHistoryEnums;
use App\Models\PointGiftRedeem;
use App\Models\PointHistory;
use App\Repositories\PointHistoryRepository;
use App\Repositories\UserRepository;
use App\Services\Logic\Point\PointHistoryService;

class PointGiftRefundPointHistory extends PointHistoryService
{

    public function handle(PointGiftRedeem $redeem)
    {
        $eventId = $redeem->id;
        $eventType = PointHistoryEnums::EVENT_POINT_GIFT_REFUND;
        $eventPoint = $redeem->gift_point;

        $historyRepo = new PointHistoryRepository();

        $history = $historyRepo->findEventHistory($eventId, $eventType);

        if ($history) return;

        $userRepo = new UserRepository();

        $user = $userRepo->findById($redeem->user_id);

        $eventInfo = [
            'point_gift_redeem' => [
                'id' => $redeem->id,
                'gift_id' => $redeem->gift_id,
                'gift_name' => $redeem->gift_name,
                'gift_type' => $redeem->gift_type,
                'gift_point' => $redeem->gift_point,
            ]
        ];

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
