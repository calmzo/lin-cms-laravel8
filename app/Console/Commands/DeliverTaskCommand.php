<?php

namespace App\Console\Commands;

use App\Enums\OrderEnums;
use App\Enums\TaskEnums;
use App\Models\Course;
use App\Models\Task;
use App\Models\Order;
use App\Models\User;
use App\Models\Vip;
use App\Services\Logic\Deliver\CourseDeliverService;
use App\Services\Logic\Deliver\VipDeliverService;
use App\Services\Logic\Point\History\OrderConsumeService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Lib\Notice\OrderFinish as OrderFinishNotice;

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
        foreach ($tasks as $task) {
            $order = Order::query()->find($task->item_id);

            try {

                DB::beginTransaction();

                switch ($order->item_type) {
                    case OrderEnums::ITEM_COURSE:
                        $this->handleCourseOrder($order);
                        break;
                    case OrderEnums::ITEM_VIP:
                        $this->handleVipOrder($order);
                        break;
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
                Log::channel('deliver')->error('Deliver Task Exception ' . json_encode([
                        'file' => $e->getFile(),
                        'line' => $e->getLine(),
                        'message' => $e->getMessage(),
                        'task' => $task->toArray(),
                    ]));
            }

            if ($task->status == TaskEnums::STATUS_FINISHED) {
                //加积分
                $this->handleOrderConsumePoint($order);
                //通知任务
                $this->handleOrderFinishNotice($order);
            } elseif ($task->status == TaskEnums::STATUS_FAILED) {
                $this->handleOrderRefund($order);
            }
        }

        echo '------ end deliver task ------' . PHP_EOL;
    }


    protected function handleCourseOrder(Order $order)
    {

        $course = Course::query()->find($order->item_id);

        $user = User::query()->find($order->user_id);
        $service = new CourseDeliverService();

        $service->handle($course, $user);
    }

    protected function handleVipOrder(Order $order)
    {

        $vip = Vip::query()->find($order->item_id);

        $user = User::query()->find($order->user_id);

        $service = new VipDeliverService();

        $service->handle($vip, $user);

        /**
         * 先下单购买课程，发现会员有优惠，于是购买会员，再回头购买课程
         * 自动关闭未支付订单，让用户可以使用会员价再次下单
         */
        $this->closePendingOrders($user->id);
    }

    /**
     * 关闭未支付订单
     * @param $userId
     */
    protected function closePendingOrders($userId)
    {
        $itemTypes = [
            OrderEnums::ITEM_COURSE,
            OrderEnums::ITEM_PACKAGE,
        ];

        $status = OrderEnums::STATUS_PENDING;

        Order::query()
            ->where('user_id', $userId)
            ->where('status', $status)
            ->whereIn('item_type', $itemTypes)
            ->update(['status' => OrderEnums::STATUS_CLOSED]);
    }


    protected function noMatchedHandler(Order $order)
    {
        throw new \RuntimeException("No Matched Handler For Order: {$order->id}");
    }

    protected function handleOrderConsumePoint(Order $order)
    {
        $service = new OrderConsumeService();
        $service->handle($order);
    }

    protected function handleOrderFinishNotice(Order $order)
    {
        $notice = new OrderFinishNotice();

        $notice->createTask($order);
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
