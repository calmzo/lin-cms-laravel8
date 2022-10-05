<?php

namespace App\Caches;

use App\Models\Chapter as ChapterModel;
use App\Models\ChapterLive;
use App\Models\ChapterLive as ChapterLiveModel;
use App\Repos\Chapter as ChapterRepo;
use App\Repos\Course as CourseRepo;
use App\Repos\User as UserRepo;
use App\Repositories\ChapterRepository;
use App\Repositories\CourseRepository;
use App\Repositories\UserRepository;
use Phalcon\Mvc\Model\Resultset;
use Phalcon\Mvc\Model\ResultsetInterface;

class IndexLiveListCache extends Cache
{

    protected $lifetime = 1 * 86400;

    public function getLifetime()
    {
        return $this->lifetime;
    }

    public function getKey($id = null)
    {
        return 'index_live_list';
    }

    public function getContent($id = null)
    {
        $limit = 8;

        $lives = $this->findChapterLives();

        if ($lives->count() == 0) return [];
        $chapterIds = $lives->pluck('chapter_id')->toArray();

        $chapterRepo = new ChapterRepository();

        $chapters = $chapterRepo->findByIds($chapterIds);
        $chapterMapping = $chapters->keyBy('id');

        $courseIds = $lives->pluck('course_id')->toArray();

        $courseRepo = new CourseRepository();

        $courses = $courseRepo->findByIds($courseIds);
        $teacherIds = $courses->pluck('teacher_id')->toArray();
        $userRepo = new UserRepository();

        $users = $userRepo->findByIds($teacherIds);
        $courseMapping = $courses->keyBy('id');
        $userMapping = $users->keyBy('id');

        $result = [];

        $flag = [];

        foreach ($lives as $live) {

            $chapter = $chapterMapping[$live->chapter_id] ?? (object)[];
            $course = $courseMapping[$chapter->course_id] ?? (object)[];
            $teacher = $userMapping[$course->teacher_id] ?? (object)[];

            $teacherInfo = [
                'id' => $teacher->id ?? 0,
                'name' => $teacher->name ?? '',
                'title' => $teacher->title ?? '',
                'avatar' => $teacher->avatar ?? '',
            ];

            $chapterInfo = [
                'id' => $chapter->id ?? 0,
                'title' => $chapter->title ?? '',
            ];

            $courseInfo = [
                'id' => $course->id ?? 0,
                'title' => $course->title ?? '',
                'cover' => $course->cover ?? '',
                'teacher' => $teacherInfo ?? '',
            ];

            if (!isset($flag[$course->id]) && count($flag) < $limit) {
                $flag[$course->id] = 1;
                $result[] = [
                    'id' => $live->id ?? 0,
                    'status' => $live->status ?? 0,
                    'start_time' => $live->start_time ?? 0,
                    'end_time' => $live->end_time ?? 0,
                    'course' => $courseInfo,
                    'chapter' => $chapterInfo,
                ];
            }
        }

        return $result;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    protected function findChapterLives()
    {
        $startTime = strtotime('today');
        $endTime = strtotime('+30 days');

        return ChapterLive::query()
            ->whereHas('chapter', function ($q) {
                $q->where('published', 1);
            })
            ->whereBetween('start_time', [$startTime, $endTime])
            ->orderBy('start_time')
            ->get();
    }

}
