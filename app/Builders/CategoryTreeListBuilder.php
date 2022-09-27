<?php

namespace App\Builders;

use App\Models\Category;

class CategoryTreeListBuilder
{

    public function handle($type)
    {
        $topCategories = $this->findTopCategories($type);

        if ($topCategories->count() == 0) {
            return [];
        }

        $list = [];

        foreach ($topCategories as $category) {
            $list[] = [
                'id' => $category->id,
                'name' => $category->name,
                'alias' => $category->alias,
                'icon' => $category->icon,
                'children' => $this->handleChildren($category),
            ];
        }

        return $list;
    }

    protected function handleChildren(Category $category)
    {
        $subCategories = $this->findChildCategories($category->id);

        if ($subCategories->count() == 0) {
            return [];
        }

        $list = [];

        foreach ($subCategories as $category) {
            $list[] = [
                'id' => $category->id,
                'name' => $category->name,
                'alias' => $category->alias,
                'icon' => $category->icon,
            ];
        }

        return $list;
    }

    /**
     * @param $type
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    protected function findTopCategories($type)
    {
        $list = Category::query()
            ->where('parent_id', 0)
            ->where('published', 1)
//            ->where('deleted', 0)
            ->where('type', $type)
            ->orderBy('priority')
            ->get();
        return $list;
    }

    /**
     * @param $parentId
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    protected function findChildCategories($parentId)
    {
        $list = Category::query()->where('parent_id', $parentId)->where('published', 1)->orderBy('priority')->get();
        return $list;
    }

}
