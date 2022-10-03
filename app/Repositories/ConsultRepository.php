<?php

namespace App\Repositories;

use App\Models\Consult;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ConsultRepository extends BaseRepository
{

    public function paginate($where = [], $sort = 'latest', $page = 1, $count = 15): LengthAwarePaginator
    {
        $query = Consult::query();

        if (!empty($where['id'])) {
            $query->where('id', $where['id']);
        }

        if (!empty($where['course_id'])) {
            $query->where('course_id', $where['course_id']);
        }

        if (!empty($where['chapter_id'])) {
            $query->where('chapter_id', $where['chapter_id']);
        }

        if (!empty($where['owner_id'])) {
            $query->where('owner_id', $where['owner_id']);
        }

        if (!empty($where['replied'])) {
            $query->where('reply_time', '>', 0);
        }

        if (isset($where['private'])) {
            $query->where('private', $where['private']);
        }

        if (!empty($where['published'])) {
            if (is_array($where['published'])) {
                $query->whereIn('published', $where['published']);
            } else {
                $query->where('published', $where['published']);
            }
        }

        if ($sort == 'reported') {
            $query->where('report_count', '>', 0);
        }

        switch ($sort) {
            case 'priority':
                $query->orderBy('priority')->orderByDesc('id');
            default:
                $query->orderByDesc('id');
                break;
        }

        return $query->paginate($count, ['*'], 'page', $page);
    }

}
