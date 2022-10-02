<?php

namespace App\Services\Admin;

use App\Models\ArticleTag;
use App\Models\Tag;
use App\Repositories\TagRepository;

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
    public function getTags($params)
    {
        $page = $params['page'] ?? 0;
        $sort = $params['sort'] ?? 'latest';
        $count = $params['limit'] ?? 15;

        list($page, $count) = paginateFormat($page, $count);
        $tagRepo = new TagRepository();
        return $tagRepo->paginate($params, $sort, $page, $count);


    }


    /**
     * @param $params
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function searchTags($params)
    {
        $page = $params['page'] ?? 0;
        $sort = $params['sort'] ?? 'latest';
        $count = $params['limit'] ?? 15;

        list($page, $count) = paginateFormat($page, $count);
        $tagRepo = new TagRepository();
        return $tagRepo->paginate($params, $sort, $page, $count);
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
