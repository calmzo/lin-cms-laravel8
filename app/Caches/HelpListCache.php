<?php

namespace App\Caches;

use App\Enums\CategoryEnums;
use App\Models\Category;
use App\Models\Help;

class HelpListCache extends Cache
{

    protected $lifetime = 365 * 86400;

    public function getLifetime()
    {
        return $this->lifetime;
    }

    public function getKey($id = null)
    {
        return 'help_list';
    }

    public function getContent($id = null)
    {

        $categories = $this->findCategories();

        if ($categories->count() == 0) {
            return [];
        }

        $result = [];

        foreach ($categories as $category) {

            $item = [];

            $item['category'] = [
                'id' => $category->id,
                'name' => $category->name,
            ];

            $item['helps'] = [];

            $helps = $this->findHelps($category->id);

            if ($helps->count() > 0) {
                foreach ($helps as $help) {
                    $item['helps'][] = [
                        'id' => $help->id,
                        'title' => $help->title,
                    ];
                }
            }

            $result[] = $item;
        }

        return $result;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    protected function findCategories()
    {
        return Category::query()
            ->where('type', CategoryEnums::TYPE_HELP)
            ->where('level', 1)
            ->where('published', 1)
            ->orderBy('priority')
            ->get();
    }

    /**
     * @param $categoryId
     * @return mixed
     */
    protected function findHelps($categoryId)
    {
        return Help::query()
            ->where('category_id', $categoryId)
            ->where('published', 1)
            ->orderBy('priority')
            ->get();
    }

}
