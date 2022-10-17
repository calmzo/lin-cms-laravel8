<?php

namespace App\Lib\Notice;

use App\Enums\TaskEnums;
use App\Models\Order;
use App\Models\Task;
use App\Lib\Notice\Sms\OrderFinish as SmsOrderFinishNotice;
use App\Lib\Notice\WeChat\OrderFinish as WeChatOrderFinishNotice;
use App\Repositories\OrderRepository;
use App\Repositories\UserRepository;
use App\Repositories\WechatSubscribeRepository;


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

        $orderRepo = new OrderRepository();
        $order = $orderRepo->findById($orderId);

        $userRepo = new UserRepository();
        $user = $userRepo->findById($order->user_id);

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

        $subscribeRepo = new WechatSubscribeRepository();

        $subscribe = $subscribeRepo->findByUserId($order->user_id);
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
        $task->item_info = $itemInfo;
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

        $template = $oa['notice_template'];

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
