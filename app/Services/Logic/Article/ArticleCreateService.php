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
        $this->checkDailyArticleLimit($user);
        $data = $this->handleParamsData($params);

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
