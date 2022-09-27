<?php

namespace App\Services;

use App\Models\ArticleTag;
use App\Models\Tag;

class TagService
{

    /**
     * @param $page
     * @param $count
     * @param string|null $start
     * @param string|null $end
     * @param string|null $name
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getTags($page, $count, string $start = null, string $end = null, string $name = null)
    {
        list($page, $count) = paginate($page, $count);
        $query = Tag::query();
        if ($name) {
            $query->where('name', 'like', '%' . $name . '%');
        }
        if ($start && $end) {
            $query->whereBetween('create_time', [$start, $end]);
        }
        return $query->orderByDesc('create_time')->paginate($count, ['*'], 'page', $page);
    }


    /**
     * @param int $page
     * @param int $count
     * @param string|null $start
     * @param string|null $end
     * @param string|null $name
     * @param string|null $keyword
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function searchTags(int    $page, int $count, string $start = null,
                                      string $end = null, string $name = null, string $keyword = null)
    {
        list($page, $count) = paginate($page, $count);
        $query = Tag::query();
        if ($name) {
            $query->where('name', 'like', '%' . $name . '%');
        }
        if ($start && $end) {
            $query->whereBetween('create_time', [$start, $end]);
        }

        if ($keyword) {
            $query->where('alias', 'like', '%' . $keyword . '%');
        }

        $res = $query->orderByDesc('create_time')->paginate($count, ['*'], 'page', $page);
        return $res;
    }


    public function createTag(array $params): Tag
    {
        $data = [
            'name' => $params['name'] ?? ''
        ];
        $tag = Tag::query()->create($data);
        return $tag;
    }


    public function countArticles($tagId)
    {
        return ArticleTag::query()->where('tag_id', $tagId)->count();
    }

    /**
     * 根据ids获取标签列表
     * @param $ids
     * @param string $columns
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function findByIds($ids, $columns = '*')
    {
        return Tag::query()
            ->whereIn('id', $ids)
            ->get($columns);
    }
}
