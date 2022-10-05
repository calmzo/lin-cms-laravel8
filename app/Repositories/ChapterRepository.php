<?php

namespace App\Repositories;

use App\Models\Chapter;
use App\Models\ChapterLive;
use App\Models\ChapterRead;
use App\Models\ChapterVod;

class ChapterRepository extends BaseRepository
{

    public function findById($id)
    {
        return Chapter::query()->find($id);
    }

    public function findByIds($ids, $columns = '*')
    {
        return Chapter::query()
            ->whereIn('id', $ids)
            ->get($columns);
    }

    public function findAll($where = [])
    {
        $query = Chapter::query();

        if (isset($where['parent_id'])) {
            $query->where('parent_id', $where['parent_id']);
        }

        if (isset($where['course_id'])) {
            $query->where('course_id', $where['course_id']);
        }

        if (isset($where['published'])) {
            $query->where('published', $where['published']);
        }

        $query->orderBy('priority');

        return $query->get();
    }

    public function findChapterVod($chapterId)
    {
        return ChapterVod::query()->where('chapter_id', $chapterId)->first();
    }

    public function findChapterLive($chapterId)
    {
        return ChapterLive::query()->where('chapter_id', $chapterId)->first();
    }

    public function findChapterRead($chapterId)
    {
        return ChapterRead::query()->where('chapter_id', $chapterId)->first();
    }

}
