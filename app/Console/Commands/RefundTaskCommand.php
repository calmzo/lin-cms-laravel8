<?php

namespace App\Console\Commands;

use App\Enums\OrderEnums;
use App\Enums\RefundEnums;
use App\Enums\TaskEnums;
use App\Enums\TradeEnums;
use App\Lib\Notice\RefundFinish;
use App\Lib\Pay\Alipay;
use App\Lib\Pay\Wxpay;
use App\Models\Refund;
use App\Models\Task;
use App\Models\Trade;
use App\Models\Order;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RefundTaskCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'refund_task';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $tasks = $this->findTasks(30);

        echo sprintf('pending tasks: %s', $tasks->count()) . PHP_EOL;

        if ($tasks->count() == 0) return;

        echo '------ start refund task ------' . PHP_EOL;

        foreach ($tasks as $task) {
            $refund = $task->refund ?? null;
            $trade = Trade::query()->find($refund->trade_id);
            $order = Order::query()->find($refund->order_id);
            if ($refund->status != RefundEnums::STATUS_APPROVED) {
                $task->status = TaskEnums::STATUS_CANCELED;
                $task->save();
                continue;
            }

            try {

                DB::beginTransaction();

                $this->handleTradeRefund($trade, $refund);
                $this->handleOrderRefund($order);

                $refund->status = RefundEnums::STATUS_FINISHED;
                $refund->save();

                $trade->status = TradeEnums::STATUS_REFUNDED;
                $trade->save();

                $order->status = OrderEnums::STATUS_REFUNDED;
                $order->save();

                $task->status = TaskEnums::STATUS_FINISHED;
                $task->save();

                DB::commit();

                $this->handleRefundFinishNotice($refund);

            } catch (\Exception $e) {

                DB::rollBack();
                $task->try_count += 1;
                $task->priority += 1;

                if ($task->try_count > $task->max_try_count) {
                    $task->status = TaskEnums::STATUS_FAILED;
                }

                $task->save();
                Log::channel('refund')->error('Refund Task Exception ' . json_encode([
                        'file' => $e->getFile(),
                        'line' => $e->getLine(),
                        'message' => $e->getMessage(),
                        'task' => $task->toArray(),
                    ]));
            }

            if ($task->status == TaskEnums::STATUS_FAILED) {
                $refund->status = RefundEnums::STATUS_FAILED;
                $refund->save();
            }
        }

        echo '------ end refund task ------' . PHP_EOL;
    }


    /**
     * 处理交易退款
     *
     * @param Trade $trade
     * @param Refund $refund
     */
    protected function handleTradeRefund(Trade $trade, Refund $refund)
    {
        $response = false;

        if ($trade->channel == TradeEnums::CHANNEL_ALIPAY) {

            $alipay = new Alipay();

            $response = $alipay->refund($refund);

        } elseif ($trade->channel == TradeEnums::CHANNEL_WXPAY) {

            $wxpay = new Wxpay();

            $response = $wxpay->refund($refund);
        }

        if (!$response) {
            throw new \RuntimeException('Trade Refund Failed');
        }
    }

    /**
     * 处理订单退款
     *
     * @param Order $order
     */
    protected function handleOrderRefund(Order $order)
    {
//        switch ($order->item_type) {
//            case OrderEnums::ITEM_COURSE:
//                $this->handleCourseOrderRefund($order);
//                break;
//            case OrderEnums::ITEM_PACKAGE:
//                $this->handlePackageOrderRefund($order);
//                break;
//            case OrderEnums::ITEM_VIP:
//                $this->handleVipOrderRefund($order);
//                break;
//            case OrderEnums::ITEM_REWARD:
//                $this->handleRewardOrderRefund($order);
//                break;
//            case OrderEnums::ITEM_TEST:
//                $this->handleTestOrderRefund($order);
//                break;
//        }
    }



    protected function findTasks($limit = 30)
    {
        $itemType = TaskEnums::TYPE_REFUND;
        $status = TaskEnums::STATUS_PENDING;
        $createTime = date('Y-m-d H:i:s', strtotime('-3 days'));

        return Task::query()
            ->where('item_type', $itemType)
            ->where('status', $status)
            ->where('create_time', '>', $createTime)
            ->with('refund')
            ->orderBy('priority')
            ->limit($limit)
            ->get();
    }

    /**
     * @param Refund $refund
     */
    protected function handleRefundFinishNotice(Refund $refund)
    {
        $notice = new RefundFinish();

        $notice->createTask($refund);
    }
}
