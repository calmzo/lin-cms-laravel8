<?php

namespace App\Lib\Notice;

use App\Models\Task as Task;
use App\Models\User as User;
use App\Models\WechatSubscribe;
use App\Services\Logic\Notice\WeChat\AccountLogin as WeChatAccountLoginNotice;
use App\Traits\Client as ClientTrait;

class AccountLogin
{

    use ClientTrait;

    public function handleTask(Task $task)
    {
        $wechatNoticeEnabled = $this->wechatNoticeEnabled();

        if (!$wechatNoticeEnabled) return;

        $params = $task->item_info;

        $userId = $task->item_info['user']['id'];


        $subscribe = WechatSubscribe::query()->where('user_id', $userId)->first();

        if ($subscribe) {

            $notice = new WeChatAccountLoginNotice();

            return $notice->handle($subscribe, $params);
        }
    }

    public function createTask(User $user)
    {
        $wechatNoticeEnabled = $this->wechatNoticeEnabled();

        if (!$wechatNoticeEnabled) return;

        $task = new Task();

        $loginIp = $this->getClientIp();
        $loginRegion = kg_ip2region($loginIp);

        $itemInfo = [
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
            ],
            'login_ip' => $loginIp,
            'login_region' => $loginRegion,
            'login_time' => time(),
        ];

        $task->item_id = $user->id;
        $task->item_info = $itemInfo;
        $task->item_type = Task::TYPE_NOTICE_ACCOUNT_LOGIN;
        $task->priority = Task::PRIORITY_LOW;
        $task->status = Task::STATUS_PENDING;
        $task->max_try_count = 1;

        $task->create();
    }

    public function wechatNoticeEnabled()
    {
        $oa = config('wechat');

        if ($oa['enabled'] == 0) return false;

        $template = json_decode($oa['notice_template'], true);

        return $template['account_login']['enabled'] == 1;
    }

}
