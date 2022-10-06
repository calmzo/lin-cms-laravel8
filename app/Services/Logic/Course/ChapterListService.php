<?php

namespace App\Services\Logic\Course;

use App\Caches\CourseChapterListCache;
use App\Models\Course;
use App\Models\User;
use App\Repositories\CourseRepository;
use App\Repositories\UserRepository;
use App\Services\Logic\LogicService;
use App\Services\Token\AccountLoginTokenService;
use App\Traits\CourseTrait;

class ChapterListService extends LogicService
{

    use CourseTrait;

    public function handle($id)
    {
        $course = $this->checkCourse($id);

        $uid = AccountLoginTokenService::userId();
        $userRepo = new UserRepository();
        $user = $userRepo->findById($uid);

        $this->setCourseUser($course, $user);

        return $this->getChapters($course, $user);
    }

    protected function getChapters(Course $course, User $user)
    {
        $cache = new CourseChapterListCache();

        $chapters = $cache->get($course->id);

        if (count($chapters) == 0) return [];

        if ($user->id > 0 && $this->courseUser) {
            $chapters = $this->handleLoginUserChapters($chapters, $course, $user);
        } else {
            $chapters = $this->handleGuestUserChapters($chapters);
        }

        return $chapters;
    }

    protected function handleLoginUserChapters(array $chapters, Course $course, User $user)
    {
        $mapping = $this->getLearningMapping($course->id, $user->id, $this->courseUser->plan_id);

        foreach ($chapters as &$chapter) {
            foreach ($chapter['children'] as &$lesson) {
                $owned = ($this->ownedCourse || $lesson['free'] == 1) && $lesson['published'] == 1;
                $lesson['me'] = [
                    'owned' => $owned ? 1 : 0,
                    'progress' => $mapping[$lesson['id']]['progress'] ?? 0,
                    'duration' => $mapping[$lesson['id']]['duration'] ?? 0,
                ];
            }
        }

        return $chapters;
    }

    protected function handleGuestUserChapters(array $chapters)
    {
        foreach ($chapters as &$chapter) {
            foreach ($chapter['children'] as &$lesson) {
                $owned = ($this->ownedCourse || $lesson['free'] == 1) && $lesson['published'] == 1;
                $lesson['me'] = [
                    'owned' => $owned ? 1 : 0,
                    'progress' => 0,
                    'duration' => 0,
                ];
            }
        }

        return $chapters;
    }

    protected function getLearningMapping($courseId, $userId, $planId)
    {
        $courseRepo = new CourseRepository();

        $userLearnings = $courseRepo->findUserLearnings($courseId, $userId, $planId);

        if ($userLearnings->count() == 0) return [];

        $mapping = [];

        foreach ($userLearnings as $learning) {
            $mapping[$learning->chapter_id] = [
                'progress' => $learning->progress,
                'duration' => $learning->duration,
                'consumed' => $learning->consumed,
            ];
        }

        return $mapping;
    }

}
