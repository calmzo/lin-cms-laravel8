<?php

namespace App\Lib\Notice;

use App\Enums\TaskEnums;
use App\Models\PointGiftRedeem;
use App\Models\Task;
use App\Repositories\PointGiftRedeemRepository;
use App\Repositories\UserRepository;
use App\Repositories\WechatSubscribeRepository;
use App\Lib\Notice\Sms\GoodsDeliver as SmsGoodsDeliverNotice;
use App\Lib\Notice\WeChat\GoodsDeliver as WeChatGoodsDeliverNotice;

class PointGoodsDeliver
{

    public function handleTask(Task $task)
    {
        $wechatNoticeEnabled = $this->wechatNoticeEnabled();
        $smsNoticeEnabled = $this->smsNoticeEnabled();

        if (!$wechatNoticeEnabled && !$smsNoticeEnabled) return;

        $redeemId = $task->item_info['point_redeem']['id'];

        $redeemRepo = new PointGiftRedeemRepository();

        $redeem = $redeemRepo->findById($redeemId);

        $userRepo = new UserRepository();

        $user = $userRepo->findById($redeem->user_id);

        $params = [
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
            ],
            'goods_name' => $redeem->gift_name,
            'order_sn' => date('YmdHis') . rand(1000, 9999),
            'deliver_time' => time(),
        ];

        $subscribeRepo = new WechatSubscribeRepository();

        $subscribe = $subscribeRepo->findByUserId($user->id);

        if ($wechatNoticeEnabled && $subscribe) {
            $notice = new WeChatGoodsDeliverNotice();
            $notice->handle($subscribe, $params);
        }

        if ($smsNoticeEnabled) {
            $notice = new SmsGoodsDeliverNotice();
            $notice->handle($user, $params);
        }
    }

    public function createTask(PointGiftRedeem $redeem)
    {
        $wechatNoticeEnabled = $this->wechatNoticeEnabled();
        $smsNoticeEnabled = $this->smsNoticeEnabled();

        if (!$wechatNoticeEnabled && !$smsNoticeEnabled) return;

        $task = new Task();

        $itemInfo = [
            'point_gift_redeem' => ['id' => $redeem->id],
        ];

        $task->item_id = $redeem->id;
        $task->item_info = $itemInfo;
        $task->item_type = TaskEnums::TYPE_NOTICE_POINT_GOODS_DELIVER;
        $task->priority = TaskEnums::PRIORITY_MIDDLE;
        $task->status = TaskEnums::STATUS_PENDING;

        $task->save();
    }

    public function wechatNoticeEnabled()
    {
        $oa = config('wechat.oa');

        if ($oa['enabled'] == 0) return false;

        $template = $oa['notice_template'];

        $result = $template['goods_deliver']['enabled'] ?? 0;

        return $result == 1;
    }

    public function smsNoticeEnabled()
    {
        $sms = config('sms');

        $template = $sms['template'];

        $result = $template['goods_deliver']['enabled'] ?? 0;

        return $result == 1;
    }

}
