<?php

namespace App\Services\Admin;


class ReportService
{

    public function getArticles($params)
    {
        $page = $params['page'] ?? 0;
        $limit = $params['count'] ?? 15;

        $articleService = new ArticleService();
        return $articleService->paginate($params, 'reported', $page, $limit);
    }


}
