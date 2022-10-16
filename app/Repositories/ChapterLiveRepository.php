<?php

namespace App\Repositories;

use App\Models\Chapter;
use App\Models\ChapterLive;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ChapterLiveRepository extends BaseRepository
{
    public function paginate($where = [], $sort = 'latest', $page = 1, $limit = 15): LengthAwarePaginator
    {
        $query = ChapterLive::query();
        if (!empty($where['start_time'])) {
            $query->where('start_time', '>', $where['start_time']);
        }

        if (!empty($where['end_time'])) {
            $query->where('start_time', '<', $where['end_time']);
        }

        if (!empty($where['course_id'])) {
            $courseId = $where['course_id'];
            $query->whereHas('chapter', function ($q) use ($courseId) {
                $q->where('course_id', $courseId);
            });
        }

        if (isset($where['published'])) {
            $published = $where['published'];
            $query->whereHas('chapter', function ($q) use ($published) {
                $q->where('published', $published);
            });
        }

        switch ($sort) {
            default:
                $query->orderBy('start_time');
                break;
        }

        return $query->paginate($limit, ['*'], 'page', $page);
    }

    public function findById($id)
    {
        return ChapterLive::query()->find($id);
    }

}
