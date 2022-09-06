<?php

namespace App\Lib\Notice;

use App\Enums\TaskEnums;
use App\Models\Order;
use App\Models\Task;
use App\Lib\Notice\Sms\OrderFinish as SmsOrderFinishNotice;
use App\Models\Users\User;
use App\Models\WechatSubscribe;
use App\Lib\Notice\WeChat\OrderFinish as WeChatOrderFinishNotice;


class OrderFinish
{

    public function handleTask(Task $task)
    {
        $wechatNoticeEnabled = $this->wechatNoticeEnabled();
        $smsNoticeEnabled = $this->smsNoticeEnabled();
        if (!$wechatNoticeEnabled && !$smsNoticeEnabled) {
            return;
        }

        $orderId = $task->item_info['order']['id'];
        $order = Order::query()->find($orderId);

        $user = User::query()->find($order->user_id);

        $params = [
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
            ],
            'order' => [
                'sn' => $order->sn,
                'subject' => $order->subject,
                'amount' => $order->amount,
                'create_time' => $order->create_time,
                'update_time' => $order->update_time,
            ],
        ];

        $subscribe = WechatSubscribe::query()->where('user_id', $order->user_id)->first();
        $notice = new WeChatOrderFinishNotice();
        $notice->handle($subscribe, $params);

        $notice = new SmsOrderFinishNotice();
        $notice->handle($user, $params);
    }

    public function createTask(Order $order)
    {
        $wechatNoticeEnabled = $this->wechatNoticeEnabled();
        $smsNoticeEnabled = $this->smsNoticeEnabled();
        if (!$wechatNoticeEnabled && !$smsNoticeEnabled) {
            return;
        }

        $task = new Task();

        $itemInfo = [
            'order' => ['id' => $order->id],
        ];

        $task->item_id = $order->id;
        $task->item_info = json_encode($itemInfo);
        $task->item_type = TaskEnums::TYPE_NOTICE_ORDER_FINISH;
        $task->priority = TaskEnums::PRIORITY_HIGH;
        $task->status = TaskEnums::STATUS_PENDING;

        $task->save();
    }

    public function wechatNoticeEnabled()
    {
        //微信通知开关
        $oa = config('wechat.oa');

        if ($oa['enabled'] == 0) return false;

        $template = json_decode($oa['notice_template'], true);

        $result = $template['order_finish']['enabled'] ?? 0;

        return $result;
    }

    public function smsNoticeEnabled()
    {
        $sms = config('sms');
        $template = $sms['template'] ?? [];

        $result = $template['order_finish']['enabled'] ?? 0;

        return $result == 1;
    }

}
