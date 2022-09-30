<?php

namespace App\Builders;

use App\Caches\CategoryListCache;
use App\Enums\CategoryEnums;
use App\Services\UserService;

class ArticleListBuilder
{

    public function handleArticles(array $articles)
    {
        foreach ($articles as $key => $article) {
            $articles[$key]['tags'] = json_decode($article['tags'], true);
        }

        return $articles;
    }

    public function handleCategories(array $articles)
    {
        $categories = $this->getCategories();
        foreach ($articles as &$article) {
            $article['category'] = $categories[$article['category_id']] ?? (object)[];
        }
        return $articles;
    }

    public function handleUsers(array $articles)
    {
        $users = $this->getUsers($articles);
        foreach ($articles as &$article) {
            $article['owner'] = $users[$article['user_id']] ?? (object)[];
        }

        return $articles;
    }

    public function getCategories()
    {
        $cache = new CategoryListCache();

        $items = $cache->get(CategoryEnums::TYPE_ARTICLE);
        if (empty($items)) return [];
        return collect($items)->keyBy('id')->all();
    }

    public function getUsers($articles)
    {
        $ids = collect($articles)->pluck('user_id');

        $userService = new UserService();

        $users = $userService->findUserByIds($ids);
        return $users->keyBy('id')->toArray();
    }

}
