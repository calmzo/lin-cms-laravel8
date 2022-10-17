<?php

namespace App\Lib\Notice;

use App\Models\Consult;
use App\Models\Course;
use App\Models\Task;
use App\Models\User;
use App\Models\WechatSubscribe;
use App\Lib\Notice\Sms\ConsultReply as SmsConsultReplyNotice;
use App\Lib\Notice\WeChat\ConsultReply as WeChatConsultReplyNotice;

class ConsultReply
{

    public function handleTask(Task $task)
    {
        $wechatNoticeEnabled = $this->wechatNoticeEnabled();
        $smsNoticeEnabled = $this->smsNoticeEnabled();

        if (!$wechatNoticeEnabled && !$smsNoticeEnabled) return;

        $consultId = $task->item_info['consult']['id'];

        $consult = Consult::query()->find($consultId);

        $course = Course::query()->find($consult->course_id);

        $user = User::query()->find($consult->user_id);

        $replier = User::query()->find($consult->replier_id);

        $params = [
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
            ],
            'replier' => [
                'id' => $replier->id,
                'name' => $replier->name,
            ],
            'consult' => [
                'id' => $consult->id,
                'question' => $consult->question,
                'answer' => $consult->answer,
                'create_time' => $consult->create_time,
                'reply_time' => $consult->reply_time,
            ],
            'course' => [
                'id' => $course->id,
                'title' => $course->title,
            ],
        ];

        $subscribe = WechatSubscribe::query()->where('user_id', $consult->user_id)->first();
        if ($wechatNoticeEnabled && $subscribe) {
            $notice = new WeChatConsultReplyNotice();
            $notice->handle($subscribe, $params);
        }

        if ($smsNoticeEnabled) {
            $notice = new SmsConsultReplyNotice();
            $notice->handle($user, $params);
        }
    }

    public function createTask(Consult $consult)
    {
        $wechatNoticeEnabled = $this->wechatNoticeEnabled();
        $smsNoticeEnabled = $this->smsNoticeEnabled();

        if (!$wechatNoticeEnabled && !$smsNoticeEnabled) return;

        $task = new Task();

        $itemInfo = [
            'consult' => ['id' => $consult->id],
        ];

        $task->item_id = $consult->id;
        $task->item_info = $itemInfo;
        $task->item_type = Task::TYPE_NOTICE_CONSULT_REPLY;
        $task->priority = Task::PRIORITY_LOW;
        $task->status = Task::STATUS_PENDING;
        $task->max_try_count = 1;

        $task->create();
    }

    public function wechatNoticeEnabled()
    {
        $oa = config('wechat.oa');

        if ($oa['enabled'] == 0) return false;

        $template = $oa['notice_template'];

        $result = $template['consult_reply']['enabled'] ?? 0;

        return $result == 1;
    }

    public function smsNoticeEnabled()
    {
        $sms = config('sms');

        $template = $sms['template'];

        $result = $template['consult_reply']['enabled'] ?? 0;

        return $result == 1;
    }

}
