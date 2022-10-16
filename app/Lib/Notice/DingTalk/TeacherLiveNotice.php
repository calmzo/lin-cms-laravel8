<?php
namespace App\Lib\Notice\DingTalk;

use App\Enums\TaskEnums;
use App\Models\ChapterLive;
use App\Models\Task;
use App\Repositories\ChapterLiveRepository;
use App\Repositories\CourseRepository;

class TeacherLiveNotice extends DingTalkNotice
{

    public function handleTask(Task $task)
    {
        if (!$this->enabled) return;

        $liveRepo = new ChapterLiveRepository();

        $live = $liveRepo->findById($task->item_id);

        $courseRepo = new CourseRepository();

        $course = $courseRepo->findById($live->course_id);

        $content = kg_ph_replace("课程：{course.title} 计划于 {live.start_time} 开播，不要错过直播时间哦！", [
            'course.title' => $course->title,
            'live.start_time' => date('Y-m-d H:i', $live->start_time),
        ]);

        $this->atCourseTeacher($course->id, $content);
    }

    public function createTask(ChapterLive $live)
    {
        if (!$this->enabled) return;

        $task = new Task();

        $itemInfo = [
            'live' => ['id' => $live->id],
        ];

        $task->item_id = $live->id;
        $task->item_info = $itemInfo;
        $task->item_type = TaskEnums::TYPE_STAFF_NOTICE_TEACHER_LIVE;
        $task->priority = TaskEnums::PRIORITY_LOW;
        $task->status = TaskEnums::STATUS_PENDING;

        $task->save();
    }

}
