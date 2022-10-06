<?php

namespace App\Builders;

use App\Enums\CourseEnums;
use App\Models\Chapter;


class CourseChapterListBuilder extends BaseBuilder
{

    /**
     * @param int $courseId
     * @return array
     */
    public function handle($courseId)
    {
        $list = [];

        $chapters = $this->findChapters($courseId);

        if ($chapters->count() == 0) {
            return [];
        }

        foreach ($chapters as $chapter) {
            $list[] = [
                'id' => $chapter->id,
                'title' => $chapter->title,
                'model' => $chapter->model,
                'published' => $chapter->published,
                'children' => $this->handleChildren($chapter),
            ];
        }

        return $list;
    }

    /**
     * @param Chapter $chapter
     * @return array
     */
    protected function handleChildren(Chapter $chapter)
    {
        $lessons = $this->findLessons($chapter->id);

        if ($lessons->count() == 0) {
            return [];
        }

        $list = [];

        foreach ($lessons as $lesson) {

            /**
             * @var $attrs array
             */
            $attrs = $lesson->attrs;

            if ($chapter->model == CourseEnums::MODEL_VOD) {
                unset($attrs['file_id'], $attrs['file_status']);
            }

            $list[] = [
                'id' => $lesson->id,
                'title' => $lesson->title,
                'model' => $lesson->model,
                'published' => $lesson->published,
                'free' => $lesson->free,
                'attrs' => $attrs,
            ];
        }

        return $list;
    }

    /**
     * @param $courseId
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    protected function findChapters($courseId)
    {
        return Chapter::query()
            ->where('course_id', $courseId)
            ->where('parent_id', 0)
            ->orderBy('priority')
            ->orderBy('id')
            ->get();
    }

    /**
     * @param $chapterId
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    protected function findLessons($chapterId)
    {
        return Chapter::query()
            ->where('parent_id', $chapterId)
            ->orderBy('priority')
            ->orderBy('id')
            ->get();
    }

}
