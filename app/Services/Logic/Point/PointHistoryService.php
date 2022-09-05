<?php

namespace App\Services\Logic\Point;

use App\Models\UserBalance;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\PointHistory;

class PointHistoryService
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

}
