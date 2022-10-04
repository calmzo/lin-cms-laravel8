<?php

namespace App\Services\Logic\Article;


use App\Events\ArticleAfterDeleteEvent;
use App\Validators\ArticleValidator;
use App\Models\Article;
use App\Models\User;
use App\Repositories\UserRepository;
use App\Services\Logic\LogicService;
use App\Services\Sync\ArticleIndexSync;
use App\Services\Token\AccountLoginTokenService;
use App\Traits\ArticleDataTrait;
use App\Traits\UserLimitTrait;

class ArticleDeleteService extends LogicService
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
        $userRepo = new UserRepository();
        $articleCount = $userRepo->countArticles($user->id);
        $user->article_count = $articleCount;

        $user->save();
    }

    protected function rebuildArticleIndex(Article $article)
    {
        $sync = new ArticleIndexSync();

        $sync->addItem($article->id);
    }

}
