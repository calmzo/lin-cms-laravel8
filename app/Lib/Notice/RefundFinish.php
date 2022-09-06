<?php

namespace App\Lib\Notice;

use App\Enums\TaskEnums;
use App\Models\Refund;
use App\Models\Task;
use App\Models\User;
use App\Models\WechatSubscribe;
use App\Lib\Notice\Sms\RefundFinish as SmsRefundFinishNotice;
use App\Lib\Notice\WeChat\RefundFinish as WeChatRefundFinishNotice;

class RefundFinish
{

    public function handleTask(Task $task)
    {
        $wechatNoticeEnabled = $this->wechatNoticeEnabled();
        $smsNoticeEnabled = $this->smsNoticeEnabled();

        if (!$wechatNoticeEnabled && !$smsNoticeEnabled) return;
        $refundId = $task->item_info['refund']['id'];

        $refund = Refund::query()->find($refundId);

        $user = User::query()->find($refund->owner_id);

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

        $subscribe = WechatSubscribe::query()->where('user_id', $refund->owner_id)->first();

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

    public function wechatNoticeEnabled()
    {
        $oa = config('wechat.oa');

        if ($oa['enabled'] == 0) return false;

        $template = json_decode($oa['notice_template'], true);

        $result = $template['refund_finish']['enabled'] ?? 0;

        return $result == 1;
    }

    public function smsNoticeEnabled()
    {
        $sms = config('sms');

        $template = json_decode($sms['template'], true);

        $result = $template['refund_finish']['enabled'] ?? 0;

        return $result == 1;
    }

}
