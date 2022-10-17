<?php

namespace App\Lib\Notice\DingTalk;

use App\Enums\TaskEnums;
use App\Models\Consult;
use App\Models\Task;
use App\Repositories\ConsultRepository;
use App\Repositories\CourseRepository;
use App\Repositories\UserRepository;

class ConsultCreate extends DingTalkNotice
{

    public function handleTask(Task $task)
    {
        if (!$this->enabled) return;

        $consultRepo = new ConsultRepository();

        $consult = $consultRepo->findById($task->item_id);

        $userRepo = new UserRepository();

        $user = $userRepo->findById($consult->user_id);

        $courseRepo = new CourseRepository();

        $course = $courseRepo->findById($consult->course_id);

        $content = kg_ph_replace("{user.name} 对课程：{course.title} 发起了咨询：\n{consult.question}", [
            'user.name' => $user->name,
            'course.title' => $course->title,
            'consult.question' => $consult->question,
        ]);

        $this->atCourseTeacher($course->id, $content);
    }

    public function createTask(Consult $consult)
    {
        if (!$this->enabled) return;

        $keyName = "dingtalk_consult_create_notice:{$consult->owner_id}";

        $cache = $this->getCache();

        $content = $cache->get($keyName);

        if ($content) return;

        $cache->put($keyName, 1);

        $task = new Task();

        $itemInfo = [
            'consult' => ['id' => $consult->id],
        ];

        $task->item_id = $consult->id;
        $task->item_info = $itemInfo;
        $task->item_type = TaskEnums::TYPE_STAFF_NOTICE_CONSULT_CREATE;
        $task->priority = TaskEnums::PRIORITY_LOW;
        $task->status = TaskEnums::STATUS_PENDING;

        $task->save();
    }

}
