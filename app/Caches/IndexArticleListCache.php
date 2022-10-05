<?php

namespace App\Caches;

use App\Enums\ArticleEnums;
use App\Repositories\ArticleRepository;
use App\Services\Logic\Article\ArticleListService;

class IndexArticleListCache extends Cache
{

    protected $lifetime = 15 * 60;

    public function getLifetime()
    {
        return $this->lifetime;
    }

    public function getKey($id = null)
    {
        return 'index_article_list';
    }

    public function getContent($id = null)
    {
        $articleRepo = new ArticleRepository();

        $where = [
            'published' => ArticleEnums::PUBLISH_APPROVED,
            'private' => 0,
        ];

        $pager = $articleRepo->paginate($where, 'latest', 1, 10);

        $service = new ArticleListService();

        $pager = $service->handleArticles($pager);

        return $pager->items ?: [];
    }

}
