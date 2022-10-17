<?php

namespace App\Console\Commands;

use App\Enums\PointGiftEnum;
use App\Enums\PointGiftRedeemEnums;
use App\Enums\TaskEnums;
use App\Lib\Notice\DingTalk\PointGiftRedeemNotice;
use App\Models\PointGiftRedeem;
use App\Models\Task;
use App\Repositories\CourseRepository;
use App\Repositories\PointGiftRedeemRepository;
use App\Repositories\PointGiftRepository;
use App\Repositories\UserRepository;
use App\Repositories\VipRepository;
use App\Services\Logic\Deliver\CourseDeliverService;
use App\Services\Logic\Deliver\VipDeliverService;
use App\Services\Logic\Point\History\PointGiftRefundPointHistory;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PointGiftDeliverTaskCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'point_gift_deliver_task';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '积分礼品派发';

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
        $logger = Log::channel('point');

        $tasks = $this->findTasks(30);

        echo sprintf('pending tasks: %s', $tasks->count()) . PHP_EOL;

        if ($tasks->count() == 0) return;

        echo '------ start deliver task ------' . PHP_EOL;

        $redeemRepo = new PointGiftRedeemRepository();

        foreach ($tasks as $task) {

            $redeem = $redeemRepo->findById($task->item_id);

            try {

                DB::beginTransaction();

                switch ($redeem->gift_type) {
                    case PointGiftEnum::TYPE_COURSE:
                        $this->handleCourseRedeem($redeem);
                        break;
                    case PointGiftEnum::TYPE_VIP:
                        $this->handleVipRedeem($redeem);
                        break;
                    case PointGiftEnum::TYPE_GOODS:
                        $this->handleGoodsRedeem($redeem);
                        break;
                }

                $task->status = TaskEnums::STATUS_FINISHED;

                if ($task->update() === false) {
                    throw new \RuntimeException('Update Task Status Failed');
                }

                DB::commit();

            } catch (\Exception $e) {

                DB::rollBack();

                $task->try_count += 1;
                $task->priority += 1;

                if ($task->try_count > $task->max_try_count) {
                    $task->status = TaskEnums::STATUS_FAILED;
                }

                $task->update();

                $logger->error('Point Gift Deliver Exception ' . kg_json_encode([
                        'file' => $e->getFile(),
                        'line' => $e->getLine(),
                        'message' => $e->getMessage(),
                        'task' => $task->toArray(),
                    ]));
            }

            if ($task->status == TaskEnums::STATUS_FAILED) {
                $this->handlePointRefund($redeem);
            }
        }

        echo '------ end deliver task ------' . PHP_EOL;
    }

    protected function handleCourseRedeem(PointGiftRedeem $redeem)
    {
        $giftRepo = new PointGiftRepository();

        $gift = $giftRepo->findById($redeem->gift_id);

        if (!$gift) {
            throw new \RuntimeException('Gift Not Found');
        }

        $courseRepo = new CourseRepository();

        $course = $courseRepo->findById($gift->attrs['id']);

        if (!$course) {
            throw new \RuntimeException('Course Not Found');
        }

        $redeem->status = PointGiftRedeemEnums::STATUS_FINISHED;

        if ($redeem->update() === false) {
            throw new \RuntimeException('Update Point Redeem Status Failed');
        }

        $userRepo = new UserRepository();

        $user = $userRepo->findById($redeem->user_id);

        $deliverService = new CourseDeliverService();

        $deliverService->handle($course, $user);
    }

    protected function handleVipRedeem(PointGiftRedeem $redeem)
    {
        $giftRepo = new PointGiftRepository();

        $gift = $giftRepo->findById($redeem->gift_id);

        if (!$gift) {
            throw new \RuntimeException('Gift Not Found');
        }

        $vipRepo = new VipRepository();

        $vip = $vipRepo->findById($gift->attrs['id']);

        if (!$vip) {
            throw new \RuntimeException('Vip Not Found');
        }

        $redeem->status = PointGiftRedeemEnums::STATUS_FINISHED;

        if ($redeem->update() === false) {
            throw new \RuntimeException('Update Point Redeem Status Failed');
        }

        $userRepo = new UserRepository();

        $user = $userRepo->findById($redeem->user_id);

        $deliverService = new VipDeliverService();

        $deliverService->handle($vip, $user);
    }

    protected function handleGoodsRedeem(PointGiftRedeem $redeem)
    {
        $notice = new PointGiftRedeemNotice();

        $notice->createTask($redeem);
    }

    protected function handlePointRefund(PointGiftRedeem $redeem)
    {
        $service = new PointGiftRefundPointHistory();

        $service->handle($redeem);
    }

    /**
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    protected function findTasks($limit = 30)
    {
        $itemType = TaskEnums::TYPE_POINT_GIFT_DELIVER;
        $status = TaskEnums::STATUS_PENDING;
        $createTime = strtotime('-3 days');

        return Task::query()
            ->where('item_type', $itemType)
            ->where('status', $status)
            ->where('create_time', '>', $createTime)
            ->orderBy('priority')
            ->limit($limit)
            ->get();
    }

}
