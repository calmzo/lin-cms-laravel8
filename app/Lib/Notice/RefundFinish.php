<?php

namespace App\Lib\Notice;

use App\Enums\TaskEnums;
use App\Models\Refund;
use App\Models\Task;
use App\Lib\Notice\Sms\RefundFinish as SmsRefundFinishNotice;
use App\Lib\Notice\WeChat\RefundFinish as WeChatRefundFinishNotice;
use App\Repositories\RefundRepository;
use App\Repositories\UserRepository;
use App\Repositories\WechatSubscribeRepository;

class RefundFinish
{

    public function handleTask(Task $task)
    {
        $wechatNoticeEnabled = $this->wechatNoticeEnabled();
        $smsNoticeEnabled = $this->smsNoticeEnabled();

        if (!$wechatNoticeEnabled && !$smsNoticeEnabled) return;
        $refundId = $task->item_info['refund']['id'];
        $refundRepo = new RefundRepository();

        $refund = $refundRepo->findById($refundId);

        $userRepo = new UserRepository();

        $user = $userRepo->findById($refund->user_id);
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

        $subscribeRepo = new WechatSubscribeRepository();

        $subscribe = $subscribeRepo->findByUserId($refund->user_id);

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
        $task->item_info = $itemInfo;
        $task->item_type = TaskEnums::TYPE_NOTICE_REFUND_FINISH;
        $task->priority = TaskEnums::PRIORITY_MIDDLE;
        $task->status = TaskEnums::STATUS_PENDING;

        $task->save();
    }

    public function wechatNoticeEnabled()
    {
        $oa = config('wechat.oa');

        if ($oa['enabled'] == 0) return false;

        $template = $oa['notice_template'];

        $result = $template['refund_finish']['enabled'] ?? 0;

        return $result == 1;
    }

    public function smsNoticeEnabled()
    {
        $sms = config('sms');

        $template = $sms['template'];

        $result = $template['refund_finish']['enabled'] ?? 0;

        return $result == 1;
    }

}
