<?php

namespace App\Services;


use App\Repositories\CourseRatingRepository;
use App\Repositories\CourseRepository;

class CourseStatService extends BaseService
{

    public function updateLessonCount($courseId)
    {
        $courseRepo = new CourseRepository();

        $course = $courseRepo->findById($courseId);

        $lessonCount = $courseRepo->countLessons($courseId);

        $course->lesson_count = $lessonCount;

        $course->save();
    }

    public function updateUserCount($courseId)
    {
        $courseRepo = new CourseRepository();

        $course = $courseRepo->findById($courseId);

        $userCount = $courseRepo->countUsers($courseId);

        $course->user_count = $userCount;

        $course->save();
    }

    public function updateRating($courseId)
    {
        $courseRepo = new CourseRepository();

        $course = $courseRepo->findById($courseId);

        $courseRatingRepo = new CourseRatingRepository();

        $courseRating = $courseRatingRepo->findByCourseId($course->id);
        if ($courseRating) {
            $courseRating->rating = $courseRatingRepo->averageRating($course->id);
            $courseRating->rating1 = $courseRatingRepo->averageRating1($course->id);
            $courseRating->rating2 = $courseRatingRepo->averageRating2($course->id);
            $courseRating->rating3 = $courseRatingRepo->averageRating3($course->id);

            $courseRating->save();

            $course->rating = $courseRating->rating;

            $course->save();
        }

    }

    public function updateReadAttrs($courseId)
    {
        $courseRepo = new CourseRepository();

        $course = $courseRepo->findById($courseId);

        $lessons = $courseRepo->findLessons($courseId);

        if ($lessons->count() == 0) return;

        $wordCount = 0;

        $duration = 0;

        foreach ($lessons as $lesson) {

            $attrs = $lesson->attrs;

            if (isset($attrs['word_count'])) {
                $wordCount += $attrs['word_count'];
            }

            if (isset($attrs['duration'])) {
                $duration += $attrs['duration'];
            }
        }

        $attrs = $course->attrs;

        $attrs['word_count'] = $wordCount;
        $attrs['duration'] = $duration;

        $course->update(['attrs' => $attrs]);
    }

    public function updateLiveAttrs($courseId)
    {
        $courseRepo = new CourseRepository();

        $course = $courseRepo->findById($courseId);

        $lessons = $courseRepo->findLessons($course->id);

        if ($lessons->count() == 0) return;

        $scopes = [];

        foreach ($lessons as $lesson) {

            $attrs = $lesson->attrs;

            if (isset($attrs['start_time'])) {
                $scopes[] = $attrs['start_time'];
            }
        }

        if (!$scopes) return;

        $attrs = $course->attrs;

        $attrs['start_date'] = date('Y-m-d', min($scopes));
        $attrs['end_date'] = date('Y-m-d', max($scopes));

        $course->update(['attrs' => $attrs]);
    }

    public function updateVodAttrs($courseId)
    {
        $courseRepo = new CourseRepository();

        $course = $courseRepo->findById($courseId);

        $lessons = $courseRepo->findChapters($course->id);

        if ($lessons->count() == 0) return;

        $duration = 0;

        foreach ($lessons as $lesson) {

            $attrs = $lesson->attrs;

            if (isset($attrs['duration'])) {
                $duration += $attrs['duration'];
            }
        }

        $attrs = $course->attrs;

        $attrs['duration'] = $duration;

        $course->update(['attrs' => $attrs]);
    }


}
