<?php

namespace App\Console\Commands;

use App\Enums\OrderEnums;
use App\Enums\TaskEnums;
use App\Models\Task;
use App\Models\Order;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DeliverTaskCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'deliver_task';

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

        echo '------ start deliver task ------' . PHP_EOL;
        $order = new Order();
        $taskIds = $tasks->pluck('id');
        $orders = Order::query()->whereIn('id', $taskIds)->get()->keyBy('id');

        foreach ($tasks as $task) {
            $order = $orders[$task->item_id] ?? null;

            try {

                DB::beginTransaction();

                switch ($order->item_type) {
                    case OrderEnums::ITEM_COURSE:
                        //todo 发货流程...
//                        $this->handleCourseOrder($order);
                        break;
//                    case OrderEnums::ITEM_PACKAGE:
//                        $this->handlePackageOrder($order);
//                        break;
//                    case OrderEnums::ITEM_VIP:
//                        $this->handleVipOrder($order);
//                        break;
                    default:
                        $this->noMatchedHandler($order);
                        break;
                }

                $order->status = OrderEnums::STATUS_FINISHED;
                $order->save();

                $task->status = TaskEnums::STATUS_FINISHED;
                $task->save();

                DB::commit();

            } catch (\Exception $e) {

                DB::rollBack();

                $task->try_count += 1;
                $task->priority += 1;

                if ($task->try_count > $task->max_try_count) {
                    $task->status = TaskEnums::STATUS_FAILED;
                }
                $task->save();
                Log::channel('deliver')->error('Deliver Task Exception ' . kg_json_encode([
                        'file' => $e->getFile(),
                        'line' => $e->getLine(),
                        'message' => $e->getMessage(),
                        'task' => $task->toArray(),
                    ]));
            }

            if ($task->status == TaskEnums::STATUS_FINISHED) {
                $this->handleOrderConsumePoint($order);
                $this->handleOrderFinishNotice($order);
            } elseif ($task->status == TaskEnums::STATUS_FAILED) {
                $this->handleOrderRefund($order);
            }
        }

        echo '------ end deliver task ------' . PHP_EOL;
    }


    protected function noMatchedHandler(Order $order)
    {
        throw new \RuntimeException("No Matched Handler For Order: {$order->id}");
    }

    /**
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    protected function findTasks($limit = 100)
    {
        $itemType = TaskEnums::TYPE_DELIVER;
        $status = TaskEnums::STATUS_PENDING;
        $createTime = date('Y-m-d', strtotime('-7 days'));

        return Task::query()
            ->where('item_type', $itemType)
            ->where('status', $status)
            ->where('create_time', '>', $createTime)
            ->orderBy('priority')
            ->limit($limit)
            ->get();
    }

}
