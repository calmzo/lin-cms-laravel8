<?php

namespace App\Services\Logic\Chapter;

use App\Enums\CourseEnums;
use App\Enums\CourseUserEnums;
use App\Models\Chapter;
use App\Models\ChapterUser;
use App\Models\Course;
use App\Models\CourseUser;
use App\Models\User;

use App\Repositories\ChapterLikeRepository;
use App\Repositories\UserRepository;
use App\Services\Logic\ChapterTrait;
use App\Services\Logic\LogicService;
use App\Services\Token\AccountLoginTokenService;
use App\Traits\CourseTrait;

class ChapterInfoService extends LogicService
{

    /**
     * @var Course
     */
    protected $course;

    /**
     * @var User
     */
    protected $user;

    use CourseTrait;
    use ChapterTrait;

    public function handle($id)
    {
        $chapter = $this->checkChapter($id);
        $course = $this->checkCourse($chapter->course_id);

        $this->course = $course;

        $uid = AccountLoginTokenService::userId();
        $user = (new UserRepository())->findById($uid);

        $this->user = $user;

        $this->setCourseUser($course, $user);
        $this->handleCourseUser($course, $user);

        $this->setChapterUser($chapter, $user);
        $this->handleChapterUser($chapter, $user);

        return $this->handleChapter($chapter, $user);
    }

    protected function handleChapter(Chapter $chapter, User $user)
    {
        $service = new BasicInfoService();

        $result = $service->handleBasicInfo($chapter);

        /**
         * 无内容查看权限，过滤掉相关内容
         */
        if (!$this->ownedChapter) {
            if ($chapter->model == CourseEnums::MODEL_VOD) {
                $result['play_urls'] = [];
            } elseif ($chapter->model == CourseEnums::MODEL_LIVE) {
                $result['play_urls'] = [];
            } elseif ($chapter->model == CourseEnums::MODEL_READ) {
                $result['content'] = '';
            }
        }

        $result['course'] = $service->handleCourseInfo($this->course);

        $me = [
            'plan_id' => 0,
            'position' => 0,
            'joined' => 0,
            'owned' => 0,
            'liked' => 0,
        ];

        $me['joined'] = $this->joinedChapter ? 1 : 0;
        $me['owned'] = $this->ownedChapter ? 1 : 0;

        if ($user->id > 0) {

            $likeRepo = new ChapterLikeRepository();

            $like = $likeRepo->findChapterLike($chapter->id, $user->id);

            if ($like && $like->deleted == 0) {
                $me['liked'] = 1;
            }

            if ($this->courseUser) {
                $me['plan_id'] = $this->courseUser->plan_id;
            }

            if ($this->chapterUser) {
                $me['position'] = $this->chapterUser->position;
            }
        }

        $result['me'] = $me;

        return $result;
    }

    protected function handleCourseUser(Course $course, User $user)
    {
        if ($user->id == 0) return;

        if ($this->joinedCourse) return;

        if (!$this->ownedCourse) return;

        $courseUser = new CourseUser();

        $roleType = CourseUserEnums::ROLE_STUDENT;
        $sourceType = CourseUserEnums::SOURCE_FREE;

        if ($course->market_price > 0 && $course->vip_price == 0 && $user->vip == 1) {
            $sourceType = CourseUserEnums::SOURCE_VIP;
        }

        $courseUser->course_id = $course->id;
        $courseUser->user_id = $user->id;
        $courseUser->source_type = $sourceType;
        $courseUser->role_type = $roleType;

        $courseUser->save();

        $this->courseUser = $courseUser;

        $this->joinedCourse = true;

        $this->incrCourseUserCount($course);

        $this->incrUserCourseCount($user);
    }

    protected function handleChapterUser(Chapter $chapter, User $user)
    {
        if ($user->id == 0) return;

        if (!$this->joinedCourse) return;

        if (!$this->ownedChapter) return;

        if ($this->joinedChapter) return;

        $chapterUser = new ChapterUser();

        $chapterUser->plan_id = $this->courseUser->plan_id;
        $chapterUser->course_id = $chapter->course_id;
        $chapterUser->chapter_id = $chapter->id;
        $chapterUser->user_id = $user->id;

        $chapterUser->save();

        $this->chapterUser = $chapterUser;

        $this->joinedChapter = true;

        $this->incrChapterUserCount($chapter);
    }

    protected function incrUserCourseCount(UserModel $user)
    {
        $user->course_count += 1;

        $user->update();
    }

    protected function incrCourseUserCount(Course $course)
    {
        $course->user_count += 1;

        $course->save();
    }

    protected function incrChapterUserCount(Chapter $chapter)
    {
        $chapter->user_count += 1;

        $chapter->save();

        $parent = $this->checkChapter($chapter->parent_id);

        $parent->user_count += 1;

        $parent->save();

    }

}
