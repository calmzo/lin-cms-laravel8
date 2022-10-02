<?php

namespace App\Services\Logic\User\Console;

use App\Repositories\ArticleRepository;
use App\Services\Logic\Article\ArticleListService;
use App\Services\Logic\LogicService;
use App\Services\Token\AccountLoginTokenService;

class ConsoleArticleListService extends LogicService
{

    public function handle($params)
    {
        $uid = AccountLoginTokenService::userId();
        $params['user_id'] = $uid;
        $page = $params['page'] ?? 1;
        $sort = $params['sort'] ?? 'latest';
        $limit = $params['limit'] ?? 15;

        $articleRepo = new ArticleRepository();
        $pager = $articleRepo->paginate($params, $sort, $page, $limit);

        return $this->handleArticles($pager);
    }

    protected function handleArticles($pager)
    {
        $service = new ArticleListService();

        return $service->handleArticles($pager);
    }

}
