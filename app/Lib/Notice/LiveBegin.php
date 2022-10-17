<?php

namespace App\Lib\Notice;

use App\Enums\TaskEnums;
use App\Models\Chapter;
use App\Models\CourseUser;
use App\Models\Task;
use App\Repositories\ChapterRepository;
use App\Repositories\CourseRepository;
use App\Repositories\UserRepository;
use App\Lib\Notice\Sms\LiveBegin as SmsLiveBeginNotice;
use App\Lib\Notice\WeChat\LiveBegin as WeChatLiveBeginNotice;
use App\Repositories\WechatSubscribeRepository;

class LiveBegin
{

    public function handleTask(Task $task)
    {
        $wechatNoticeEnabled = $this->wechatNoticeEnabled();
        $smsNoticeEnabled = $this->smsNoticeEnabled();

        if (!$wechatNoticeEnabled && !$smsNoticeEnabled) return;

        $courseUser = $task->item_info['course_user'];
        $chapterId = $task->item_info['chapter']['id'];

        $courseRepo = new CourseRepository();

        $course = $courseRepo->findById($courseUser['course_id']);

        $userRepo = new UserRepository();

        $user = $userRepo->findById($courseUser['user_id']);

        $chapterRepo = new ChapterRepository();

        $chapter = $chapterRepo->findById($chapterId);

        $params = [
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
            ],
            'course' => [
                'id' => $course->id,
                'title' => $course->title,
            ],
            'chapter' => [
                'id' => $chapter->id,
                'title' => $chapter->title,
            ],
            'live' => [
                'start_time' => $chapter->attrs['start_time'],
                'end_time' => $chapter->attrs['end_time'],
            ],
            'course_user' => $courseUser,
        ];

        $subscribeRepo = new WechatSubscribeRepository();

        $subscribe = $subscribeRepo->findByUserId($user->id);

        if ($wechatNoticeEnabled && $subscribe) {
            $notice = new WeChatLiveBeginNotice();
            $notice->handle($subscribe, $params);
        }

        if ($smsNoticeEnabled) {
            $notice = new SmsLiveBeginNotice();
            $notice->handle($user, $params);
        }
    }

    public function createTask(Chapter $chapter, CourseUser $courseUser)
    {
        $wechatNoticeEnabled = $this->wechatNoticeEnabled();
        $smsNoticeEnabled = $this->smsNoticeEnabled();

        if (!$wechatNoticeEnabled && !$smsNoticeEnabled) return;

        $task = new Task();

        $itemInfo = [
            'course_user' => [
                'course_id' => $courseUser->course_id,
                'user_id' => $courseUser->user_id,
                'role_type' => $courseUser->role_type,
                'source_type' => $courseUser->role_type,
            ],
            'chapter' => [
                'id' => $chapter->id,
            ],
        ];

        $task->item_id = $chapter->id;
        $task->item_info = $itemInfo;
        $task->item_type = TaskEnums::TYPE_NOTICE_LIVE_BEGIN;
        $task->priority = TaskEnums::PRIORITY_LOW;
        $task->status = TaskEnums::STATUS_PENDING;
        $task->max_try_count = 1;

        $task->save();
    }

    public function wechatNoticeEnabled()
    {
        $oa = config('wechat.oa');

        if ($oa['enabled'] == 0) return false;

        $template = $oa['notice_template'];

        $result = $template['live_begin']['enabled'] ?? 0;

        return $result == 1;
    }

    public function smsNoticeEnabled()
    {
        $sms = config('sms');

        $template = $sms['template'];

        $result = $template['live_begin']['enabled'] ?? 0;

        return $result == 1;
    }

}
