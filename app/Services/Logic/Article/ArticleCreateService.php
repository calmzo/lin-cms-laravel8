<?php

namespace App\Services\Logic\Article;

use App\Enums\ArticleEnums;
use App\Events\ArticleAfterCreateEvent;
use App\Events\IncrArticleCountEvent;
use App\Lib\Validators\ArticleValidator;
use App\Models\Article;
use App\Models\User;
use App\Services\Logic\Point\History\ArticlePostPointHistoryService;
use App\Services\Token\AccountLoginTokenService;
use App\Services\UserService;
use App\Traits\ArticleDataTrait;
use App\Traits\UserLimitTrait;

class ArticleCreateService
{

    use ArticleDataTrait, UserLimitTrait;

    public function handle($params)
    {
        $user = AccountLoginTokenService::user();
        $validator = new ArticleValidator();
        $this->checkDailyArticleLimit($user);

        $data['title'] = $params['title'] ?? '';
        $data['content'] = $params['content'] ?? '';
        $data['source_type'] = $params['source_type'] ?? '';
        $data['source_url'] = $params['source_url'] ?? '';
        $data['closed'] = $params['closed'] ?? '';
        $data['private'] = $params['private'] ?? '';
        if (isset($params['category_id'])) {
            $category = $validator->checkCategory($params['category_id']);
            $data['category_id'] = $category->id;
        }
        if (isset($params['source_type'])) {
            if ($params['source_type'] != ArticleEnums::SOURCE_ORIGIN) {
                $data['source_url'] = $validator->checkSourceUrl($params['source_url']);
            }
        }
        $data['client_type'] = $this->getClientType();
        $data['client_ip'] = $this->getClientIp();
        $data['published'] = $this->getPublishStatus($user);
        $data['user_id'] = $user['id'];

        $article = Article::query()->create($data);

        if (isset($params['xm_tag_ids'])) {
            $this->saveTags($article, $params['xm_tag_ids']);
        }

        $this->saveDynamicAttrs($article);
        $this->incrUserDailyArticleCount($user);
        $this->recountUserArticles($user);

        if ($article->published == ArticleEnums::PUBLISH_APPROVED) {
            $this->handleArticlePostPoint($article);
        }

        //todo 监听文章添加
        ArticleAfterCreateEvent::dispatch($article);
        return $article;
    }

    protected function incrUserDailyArticleCount(User $user)
    {
        IncrArticleCountEvent::dispatch($user);
    }

    protected function recountUserArticles(User $user)
    {
        $userService = new UserService();

        $articleCount = $userService->countArticles($user->id);

        $user->article_count = $articleCount;

        $user->save();
    }

    protected function handleArticlePostPoint(Article $article)
    {
        if ($article->published != ArticleEnums::PUBLISH_APPROVED) return;

        $service = new ArticlePostPointHistoryService();

        $service->handle($article);
    }

}
