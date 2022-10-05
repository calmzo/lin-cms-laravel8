<?php

namespace App\Services\Logic\Article;

use App\Enums\ArticleEnums;
use App\Events\ArticleAfterCreateEvent;
use App\Events\UserDailyCounterIncrArticleCountEvent;
use App\Models\Article;
use App\Models\User;
use App\Repositories\UserRepository;
use App\Services\Logic\LogicService;
use App\Services\Logic\Point\History\ArticlePostPointHistoryService;
use App\Services\Token\AccountLoginTokenService;
use App\Traits\ArticleDataTrait;
use App\Validators\UserLimitValidator;

class ArticleCreateService extends LogicService
{

    use ArticleDataTrait;

    public function handle($params)
    {
        $user = AccountLoginTokenService::user();
        $validator = new UserLimitValidator();
        $validator->checkDailyArticleLimit($user);
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
        UserDailyCounterIncrArticleCountEvent::dispatch($user);
    }

    protected function recountUserArticles(User $user)
    {
        $userRepo = new UserRepository();
        $articleCount = $userRepo->countArticles($user->id);

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
