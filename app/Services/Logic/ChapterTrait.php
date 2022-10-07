<?php

namespace App\Services\Logic;

use App\Models\Chapter;
use App\Models\ChapterUser;
use App\Models\CourseUser;
use App\Models\User;
use App\Repositories\ChapterUserRepository;
use App\Validators\ChapterValidator;

trait ChapterTrait
{

    /**
     * @var bool
     */
    protected $ownedChapter = false;

    /**
     * @var bool
     */
    protected $joinedChapter = false;

    /**
     * @var ChapterUser|null
     */
    protected $chapterUser;

    public function checkChapterVod($id)
    {
        $validator = new ChapterValidator();

        return $validator->checkChapterVod($id);
    }

    public function checkChapterLive($id)
    {
        $validator = new ChapterValidator();

        return $validator->checkChapterLive($id);
    }

    public function checkChapterRead($id)
    {
        $validator = new ChapterValidator();

        return $validator->checkChapterRead($id);
    }

    public function checkChapter($id)
    {
        $validator = new ChapterValidator();

        return $validator->checkChapter($id);
    }

    public function checkChapterCache($id)
    {
        $validator = new ChapterValidator();

        return $validator->checkChapterCache($id);
    }

    public function setChapterUser(Chapter $chapter, User $user)
    {
        $chapterUser = null;

        /**
         * @var CourseUser $courseUser
         */
        $courseUser = $this->courseUser;

        if ($user->id > 0 && $courseUser) {
            $chapterUserRepo = new ChapterUserRepository();
            $chapterUser = $chapterUserRepo->findPlanChapterUser($chapter->id, $user->id, $courseUser->plan_id);
        }

        $this->chapterUser = $chapterUser;

        if ($chapterUser) {
            $this->joinedChapter = true;
        }

        if ($this->ownedCourse || $chapter->free) {
            $this->ownedChapter = true;
        }
    }

}
