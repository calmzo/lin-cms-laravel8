<?php

namespace App\Repositories;

use App\Models\Tag;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class TagRepository extends BaseRepository
{
    public function findAll($where = [], $sort = 'latest')
    {
        return Tag::query()->where($where)->latest()->get();
    }

    public function paginate($where = [], $sort = 'latest', $page = 1, $count = 15): LengthAwarePaginator
    {
        $query = Tag::query();

        if ($where['name']) {
            $query->where('name', 'like', '%' . $where['name'] . '%');
        }

        if ($where['keyword']) {
            $query->where('alias', 'like', '%' . $where['keyword'] . '%');
        }

        if (!empty($where['name'])) {
            $query->where('name', 'like', '%' . $where['name'] . '%');
        }

        if (!empty($where['start'] && !empty($where['end']))) {
            $query->whereBetween('create_time', [$where['start'], $where['end']]);
        }
        switch ($sort) {
            default:
                $query->orderByDesc('id');
                break;
        }
        return $query->paginate($count, ['*'], 'page', $page);
    }

    public function findByIds($ids, $columns = '*')
    {
        return Tag::query()
            ->whereIn('id', $ids)
            ->get($columns);
    }

}
