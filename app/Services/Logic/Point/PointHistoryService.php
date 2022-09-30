<?php

namespace App\Services\Logic\Point;

use App\Models\UserBalance;
use App\Services\Logic\LogicService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\PointHistory;

class PointHistoryService extends LogicService
{

    /**
     * @param PointHistory $history
     */
    protected function handlePointHistory(PointHistory $history)
    {
        $logger = Log::channel('point');

        try {

            DB::beginTransaction();
            if ($history->save() === false) {
                throw new \RuntimeException('Create Point History Failed');
            }

            $balance = UserBalance::query()->where('user_id', $history->user_id)->first();

            if ($balance) {
                $balance->user_id = $history->user_id;
                $balance->point += $history->event_point;
                $result = $balance->save();
            } else {
                $balance = new UserBalance();
                $balance->user_id = $history->user_id;
                $balance->point = $history->event_point;
                $result = $balance->save();
            }

            if ($result === false) {
                throw new \RuntimeException('Save User Balance Failed');
            }

            DB::commit();

        } catch (\Exception $e) {

            DB::rollBack();

            $logger->error('Point History Exception ' . json_encode([
                    'code' => $e->getCode(),
                    'message' => $e->getMessage(),
                ]));

            throw new \RuntimeException('sys.trans_rollback');
        }
    }

    public function findEventHistory($eventId, $eventType)
    {
        return PointHistory::query()->where('event_id', $eventId)->where('event_type', $eventType)->first();

    }


    /**
     * @param int $userId
     * @param int $eventType
     * @param string $date
     * @return int
     */
    public function sumUserDailyEventPoints($userId, $eventType, $date)
    {
        return PointHistory::query()->where('user_id', $userId)->where('event_type', $eventType)->where('create_time', '>', $date)->sum('event_point');
    }

}
