<?php

namespace App\Services\Logic\Article;

use App\Builders\ArticleListBuilder;
use App\Enums\ArticleEnums;
use App\Repositories\ArticleRepository;
use App\Services\Logic\LogicService;

class ArticleListService extends LogicService
{

    public function handle($params)
    {
        $params['published'] = ArticleEnums::PUBLISH_APPROVED;
        $params['private'] = 0;
        $params['deleted'] = 0;
        $page = $params['page'] ?? 1;
        $sort = $params['sort'] ?? 'latest';
        $limit = $params['limit'] ?? 15;

        $articleRepo = new ArticleRepository();

        $pager = $articleRepo->paginate($params, $sort, $page, $limit);

        return $this->handleArticles($pager);
    }

    public function handleArticles($paginate)
    {
        if ($paginate->total() == 0) {
            return $paginate;
        }
        $builder = new ArticleListBuilder();

        $categories = $builder->getCategories();

        $articles = collect($paginate->items())->toArray();

        $users = $builder->getUsers($articles);

        $items = [];
        foreach ($articles as $article) {

            $category = $categories[$article['category_id']] ?? (object)[];

            $owner = $users[$article['user_id']] ?? (object)[];

            $items[] = [
                'id' => $article['id'],
                'title' => $article['title'],
                'cover' => $article['cover'],
                'summary' => $article['summary'],
                'source_type' => $article['source_type'],
                'source_url' => $article['source_url'],
                'tags' => $article['tags'],
                'private' => $article['private'],
                'published' => $article['published'],
                'closed' => $article['closed'],
                'view_count' => $article['view_count'],
                'like_count' => $article['like_count'],
                'comment_count' => $article['comment_count'],
                'favorite_count' => $article['favorite_count'],
                'create_time' => $article['create_time'],
                'update_time' => $article['update_time'],
                'category' => $category,
                'owner' => $owner,
            ];
        }
        $paginate = $this->newPaginator($paginate, $items);

        return $paginate;
    }
}
