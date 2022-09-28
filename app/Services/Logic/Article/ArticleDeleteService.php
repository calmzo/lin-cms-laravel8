<?php

namespace App\Services\Logic\Article;


use App\Events\ArticleAfterDeleteEvent;
use App\Lib\Validators\ArticleValidator;
use App\Models\Article;
use App\Models\User;
use App\Services\Sync\ArticleIndexSync;
use App\Services\Token\AccountLoginTokenService;
use App\Services\UserService;
use App\Traits\ArticleDataTrait;
use App\Traits\UserLimitTrait;

class ArticleDeleteService
{

    use ArticleDataTrait, UserLimitTrait;

    public function handle($id)
    {
        $validator = new ArticleValidator();
        $article = $validator->checkArticle($id);
        $user = AccountLoginTokenService::user();

        $validator->checkOwner($user->id, $article->user_id);

        $article->delete();

        $this->recountUserArticles($user);

        $this->rebuildArticleIndex($article);
        ArticleAfterDeleteEvent::dispatch($article);
    }

    protected function recountUserArticles(User $user)
    {
        $userService = new UserService();

        $articleCount = $userService->countArticles($user->id);

        $user->article_count = $articleCount;

        $user->save();
    }

    protected function rebuildArticleIndex(Article $article)
    {
        $sync = new ArticleIndexSync();

        $sync->addItem($article->id);
    }

}
