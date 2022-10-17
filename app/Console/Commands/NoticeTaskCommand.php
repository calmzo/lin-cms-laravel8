<?php

namespace App\Console\Commands;

use App\Enums\TaskEnums;
use App\Lib\Notice\DingTalk\ServerMonitorNotice;
use App\Models\Task;
use Illuminate\Console\Command;
use App\Lib\Notice\AccountLogin as AccountLoginNotice;
use App\Lib\Notice\LiveBegin as LiveBeginNotice;
use App\Lib\Notice\PointGoodsDeliver as PointGoodsDeliverNotice;
use App\Lib\Notice\DingTalk\ConsultCreate as ConsultCreateNotice;
use App\Lib\Notice\DingTalk\TeacherLive as TeacherLiveNotice;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use App\Lib\Notice\OrderFinish as OrderFinishNotice;
use App\Lib\Notice\RefundFinish as RefundFinishNotice;
use App\Lib\Notice\ConsultReply as ConsultReplyNotice;

class NoticeTaskCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notice_task';

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
        //
        $tasks = $this->findTasks();
        if ($tasks->count() == 0) return;
        foreach ($tasks as $task) {

            try {

                switch ($task->item_type) {
                    case TaskEnums::TYPE_NOTICE_ACCOUNT_LOGIN:
                        $this->handleAccountLoginNotice($task);
                        break;
                    case TaskEnums::TYPE_NOTICE_LIVE_BEGIN:
                        $this->handleLiveBeginNotice($task);
                        break;
                    case TaskEnums::TYPE_NOTICE_ORDER_FINISH:
                        $this->handleOrderFinishNotice($task);
                        break;
                    case TaskEnums::TYPE_NOTICE_REFUND_FINISH:
                        $this->handleRefundFinishNotice($task);
                        break;
                    case TaskEnums::TYPE_NOTICE_CONSULT_REPLY:
                        $this->handleConsultReplyNotice($task);
                        break;
                    case TaskEnums::TYPE_NOTICE_POINT_GOODS_DELIVER:
                        $this->handlePointGoodsDeliverNotice($task);
                        break;
                    case TaskEnums::TYPE_STAFF_NOTICE_CONSULT_CREATE:
                        $this->handleConsultCreateNotice($task);
                        break;
                    case TaskEnums::TYPE_STAFF_NOTICE_TEACHER_LIVE:
                        $this->handleTeacherLiveNotice($task);
                        break;
                    case TaskEnums::TYPE_STAFF_NOTICE_SERVER_MONITOR:
                        $this->handleServerMonitorNotice($task);
                        break;
                    case TaskEnums::TYPE_STAFF_NOTICE_CUSTOM_SERVICE:
//                        $this->handleCustomServiceNotice($task);
                        break;
                }

                $task->status = TaskEnums::STATUS_FINISHED;

                $task->update();

            } catch (\Exception $e) {

                $task->try_count += 1;
                $task->priority += 1;

                if ($task->try_count >= $task->max_try_count) {
                    $task->status = TaskEnums::STATUS_FAILED;
                }

                $task->update();

                $logger = Log::channel('notice');
                $logger->error('Notice Process Exception ' . json_encode([
                        'file' => $e->getFile(),
                        'line' => $e->getLine(),
                        'message' => $e->getMessage(),
                        'task' => $task->toArray(),
                    ]));
            }
        }


    }

    protected function handleAccountLoginNotice(Task $task)
    {
        $notice = new AccountLoginNotice();

        $notice->handleTask($task);
    }

    protected function handleLiveBeginNotice(Task $task)
    {
        $notice = new LiveBeginNotice();

        $notice->handleTask($task);
    }

    protected function handleOrderFinishNotice(Task $task)
    {
        $notice = new OrderFinishNotice();

        $notice->handleTask($task);
    }

    protected function handleRefundFinishNotice(Task $task)
    {
        $notice = new RefundFinishNotice();

        $notice->handleTask($task);
    }

    protected function handleConsultReplyNotice(Task $task)
    {
        $notice = new ConsultReplyNotice();

        $notice->handleTask($task);
    }

    protected function handlePointGoodsDeliverNotice(Task $task)
    {
        $notice = new PointGoodsDeliverNotice();

        $notice->handleTask($task);
    }

    protected function handleConsultCreateNotice(Task $task)
    {
        $notice = new ConsultCreateNotice();

        $notice->handleTask($task);
    }

    protected function handleTeacherLiveNotice(Task $task)
    {
        $notice = new TeacherLiveNotice();

        $notice->handleTask($task);
    }

    protected function handleServerMonitorNotice(Task $task)
    {
        $notice = new ServerMonitorNotice();

        $notice->handleTask($task);
    }

    protected function handleCustomServiceNotice(Task $task)
    {
        $notice = new CustomServiceNotice();

        $notice->handleTask($task);
    }


    protected function findTasks($limit = 300)
    {
        $itemTypes = [
            TaskEnums::TYPE_NOTICE_ACCOUNT_LOGIN,
            TaskEnums::TYPE_NOTICE_LIVE_BEGIN,
            TaskEnums::TYPE_NOTICE_ORDER_FINISH,
            TaskEnums::TYPE_NOTICE_REFUND_FINISH,
            TaskEnums::TYPE_NOTICE_CONSULT_REPLY,
            TaskEnums::TYPE_NOTICE_POINT_GOODS_DELIVER,
            TaskEnums::TYPE_NOTICE_LUCKY_GOODS_DELIVER,
            TaskEnums::TYPE_STAFF_NOTICE_CONSULT_CREATE,
            TaskEnums::TYPE_STAFF_NOTICE_TEACHER_LIVE,
            TaskEnums::TYPE_STAFF_NOTICE_SERVER_MONITOR,
            TaskEnums::TYPE_STAFF_NOTICE_CUSTOM_SERVICE,
        ];

        $status = TaskEnums::STATUS_PENDING;

        $createTime = Carbon::now()->subDay();

        return Task::query()
            ->whereIn('item_type', $itemTypes)
            ->where('status', $status)
            ->where('create_time', '>', $createTime)
            ->orderBy('priority')
            ->limit($limit)
            ->get();
    }
}
