<?php

namespace App\Builders;

use App\Caches\CategoryListCache;
use App\Enums\CategoryEnums;
use App\Services\UserService;

class QuestionListBuilder
{

    public function handleCategories(array $questions)
    {
        $categories = $this->getCategories();

        foreach ($questions as $article) {
            $article['category'] = $categories[$article['category_id']] ?? (object)[];
        }

        return $questions;
    }

    public function handleUsers(array $questions)
    {
        $users = $this->getUsers($questions);

        foreach ($questions as &$question) {
            $question['owner'] = $users[$question['user_id']] ?? (object)[];
            $question['last_replier'] = $users[$question['last_replier_id']] ?? (object)[];
        }

        return $questions;
    }

    public function getCategories()
    {
        $cache = new CategoryListCache();
        $items = $cache->get(CategoryEnums::TYPE_QUESTION);
        if (empty($items)) return [];
        return collect($items)->keyBy('id')->all();
    }

    public function getUsers($questions)
    {

        $ownerIds = array_column_unique($questions, 'user_id');
        $lastReplierIds = array_column_unique($questions, 'last_replier_id');
        $ids = array_merge($ownerIds, $lastReplierIds);

        $userService = new UserService();

        $users = $userService->findUserByIds($ids);

        return $users->keyBy('id')->toArray();
    }

}
