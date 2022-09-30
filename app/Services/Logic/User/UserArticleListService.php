<?php

namespace App\Services\Logic\User;

use App\Enums\ArticleEnums;
use App\Repositories\ArticleRepository;
use App\Services\Logic\Article\ArticleListService;
use App\Services\Logic\LogicService;
use App\Traits\UserTrait;

class UserArticleListService extends LogicService
{

    use UserTrait;

    public function handle($id, $params)
    {
        $user = $this->checkUser($id);

        $params['user_id'] = $user->id;
        $params['published'] = ArticleEnums::PUBLISH_APPROVED;
        $params['private'] = 0;
        $page = $params['page'] ?? 1;
        $sort = $params['sort'] ?? 'latest';
        $limit = $params['limit'] ?? 15;
        $articleRepo = new ArticleRepository();
        $paginate = $articleRepo->paginate($params, $sort, $page, $limit);
        return $this->handleArticles($paginate);
    }

    protected function handleArticles($paginate)
    {
        $service = new ArticleListService();

        return $service->handleArticles($paginate);
    }

}
