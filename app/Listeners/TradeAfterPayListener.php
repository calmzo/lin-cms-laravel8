<?php

namespace App\Listeners;

use App\Enums\OrderEnums;
use App\Enums\TradeEnums;
use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TradeAfterPayListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        $trade = $event->trade;
        try {
            DB::beginTransaction();

            $trade->status = TradeEnums::STATUS_FINISHED;
            $trade->update();

            $order = Order::query()->where('id', $trade->order_id)->first();
            $order->status = OrderEnums::STATUS_DELIVERING;
            $order->save();

//            $task = new TaskModel();
//
//            $itemInfo = [
//                'order' => ['id' => $order->id]
//            ];
//
//            $task->item_id = $order->id;
//            $task->item_info = $itemInfo;
//            $task->item_type = TaskModel::TYPE_DELIVER;
//            $task->create();

            DB::commit();

//            /**
//             * 解除秒杀锁定
//             */
//            if ($order->promotion_type == OrderEnums::PROMOTION_FLASH_SALE) {
//                $cache = new FlashSaleLockCache();
//                $cache->delete($order->owner_id, $order->promotion_id);
//            }

        } catch (\Exception $e) {

            DB::rollBack();

            Log::error('After Pay Event Error ' . json_encode([
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'message' => $e->getMessage(),
                ]));

            throw new \RuntimeException('事务回滚');
        }
    }
}
