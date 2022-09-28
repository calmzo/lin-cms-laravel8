<?php

namespace App\Services;

use App\Enums\ArticleEnums;
use App\Exceptions\Forbidden;
use App\Exceptions\NotFoundException;
use App\Lib\Validators\ArticleValidator;
use App\Models\Article;
use App\Services\Logic\Article\ArticleCloseService;
use App\Services\Logic\Article\ArticleCreateService;
use App\Services\Logic\Article\ArticleFavoriteService;
use App\Services\Logic\Article\ArticleInfoService;
use App\Services\Logic\Article\ArticlePrivateService;

class ArticleService
{

    public function countArticles()
    {
        return Article::query()->where('published', ArticleEnums::PUBLISH_APPROVED)->count();
    }


    /**
     * @param $page
     * @param $count
     * @param string|null $start
     * @param string|null $end
     * @param string|null $name
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getArticles($page, $count, string $start = null, string $end = null, string $name = null)
    {
        list($page, $count) = paginate($page, $count);
        $query = Article::query();
        if ($name) {
            $query->where('name', 'like', '%' . $name . '%');
        }
        if ($start && $end) {
            $query->whereBetween('create_time', [$start, $end]);
        }
        return $query->orderByDesc('create_time')->paginate($count, ['*'], 'page', $page);
    }


    /**
     * @param int $page
     * @param int $count
     * @param string|null $start
     * @param string|null $end
     * @param string|null $name
     * @param string|null $keyword
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function searchArticles(int    $page, int $count, string $start = null,
                                   string $end = null, string $name = null, string $keyword = null)
    {
        list($page, $count) = paginate($page, $count);
        $query = Article::query();
        if ($name) {
            $query->where('name', 'like', '%' . $name . '%');
        }
        if ($start && $end) {
            $query->whereBetween('create_time', [$start, $end]);
        }

        if ($keyword) {
            $query->where('alias', 'like', '%' . $keyword . '%');
        }

        $res = $query->orderByDesc('create_time')->paginate($count, ['*'], 'page', $page);
        return $res;
    }


    public function createArticle(array $params): Article
    {
        $articleCreateService = new ArticleCreateService();
        $article = $articleCreateService->handle($params);

//        if ($article->published == ArticleEnums::PUBLISH_APPROVED) {
//            $location = $this->url->get(['for' => 'home.article.show', 'id' => $article->id]);
//            $msg = '发布文章成功';
//        } else {
//            $location = $this->url->get(['for' => 'home.uc.articles']);
//            $msg = '创建文章成功，管理员审核后对外可见';
//        }
        return $article;
    }

    public function getArticle($id)
    {
        $service = new ArticleInfoService();

        $article = $service->handle($id);
        if (!is_null($article['deleted'])) {
            throw new NotFoundException();
        }

        $approved = $article['published'] == ArticleEnums::PUBLISH_APPROVED;
        $owned = $article['me']['owned'] == 1;
        $private = $article['private'] == 1;
        if (!$approved && !$owned) {
            throw new NotFoundException();
        }

        if ($private && !$owned) {
            throw new Forbidden();
        }
        return $article;
    }

    public function closeArticle($id)
    {
        $service = new ArticleCloseService();

        $article = $service->handle($id);

        $msg = $article->closed == 1 ? '关闭评论成功' : '开启评论成功';

        return $msg;
    }

    public function privateArticle($id)
    {
        $service = new ArticlePrivateService();

        $article = $service->handle($id);

        $msg = $article->private == 1 ? '开启仅我可见成功' : '关闭仅我可见成功';

        return $msg;
    }

    public function favoriteArticle($id)
    {
        $service = new ArticleFavoriteService();

        $article = $service->handle($id);

        $msg = $article['action'] == 'do' ? '收藏成功' : '取消收藏成功';

        return $msg;
    }
}
