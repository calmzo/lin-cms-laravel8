<?php

namespace App\Lib\Notice\DingTalk;

use App\Enums\TaskEnums;
use App\Models\Task as Task;

class ServerMonitor extends DingTalkNotice
{

    public function handleTask(Task $task)
    {
        if (!$this->enabled) return;

        $notice = new DingTalkNotice();

        $content = $task->item_info['content'];

        $notice->atTechSupport($content);
    }

    public function createTask($content)
    {
        if (!$this->enabled) return;

        $task = new Task();

        $itemInfo = ['content' => $content];

        $task->item_id = time();
        $task->item_info = $itemInfo;
        $task->item_type = TaskEnums::TYPE_STAFF_NOTICE_SERVER_MONITOR;
        $task->priority = TaskEnums::PRIORITY_HIGH;
        $task->status = TaskEnums::STATUS_PENDING;
        $task->max_try_count = 1;

        $task->save();
    }

}