<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Lib\Notice;

use App\Enums\TaskEnums;
use App\Models\Refund;
use App\Models\Task;
use App\Models\Task as TaskModel;
use App\Repos\Refund as RefundRepo;
use App\Repos\User as UserRepo;
use App\Repos\WeChatSubscribe as WeChatSubscribeRepo;
use App\Lib\Notice\Sms\RefundFinish as SmsRefundFinishNotice;
use App\Lib\Notice\WeChat\RefundFinish as WeChatRefundFinishNotice;

class RefundFinish
{

    public function handleTask(TaskModel $task)
    {
        $wechatNoticeEnabled = $this->wechatNoticeEnabled();
        $smsNoticeEnabled = $this->smsNoticeEnabled();

        if (!$wechatNoticeEnabled && !$smsNoticeEnabled) return;

        $refundId = $task->item_info['refund']['id'];

        $refundRepo = new RefundRepo();

        $refund = $refundRepo->findById($refundId);

        $userRepo = new UserRepo();

        $user = $userRepo->findById($refund->owner_id);

        $params = [
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
            ],
            'refund' => [
                'sn' => $refund->sn,
                'subject' => $refund->subject,
                'amount' => $refund->amount,
                'create_time' => $refund->create_time,
                'update_time' => $refund->update_time,
            ],
        ];

        $subscribeRepo = new WeChatSubscribeRepo();

        $subscribe = $subscribeRepo->findByUserId($refund->owner_id);

        if ($wechatNoticeEnabled && $subscribe) {
            $notice = new WeChatRefundFinishNotice();
            $notice->handle($subscribe, $params);
        }

        if ($smsNoticeEnabled) {
            $notice = new SmsRefundFinishNotice();
            $notice->handle($user, $params);
        }
    }

    public function createTask(Refund $refund)
    {
        $task = new Task();
        $itemInfo = [
            'refund' => ['id' => $refund->id],
        ];

        $task->item_id = $refund->id;
        $task->item_info = json_encode($itemInfo);
        $task->item_type = TaskEnums::TYPE_NOTICE_REFUND_FINISH;
        $task->priority = TaskEnums::PRIORITY_MIDDLE;
        $task->status = TaskEnums::STATUS_PENDING;

        $task->save();
    }

}
