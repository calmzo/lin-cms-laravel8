<?php

namespace App\Services\Logic\Article;

use App\Events\ArticleAfterFavoriteEvent;
use App\Events\ArticleAfterUndoFavoriteEvent;
use App\Lib\Notice\ArticleFavorited;
use App\Lib\Validators\ArticleValidator;
use App\Models\Article;
use App\Models\User;
use App\Services\Logic\LogicService;
use App\Services\Token\AccountLoginTokenService;
use App\Traits\UserLimitTrait;
use App\Models\ArticleFavorite;

class ArticleFavoriteService extends LogicService
{

    use UserLimitTrait;

    public function handle($id)
    {
        $validator = new ArticleValidator();

        $article = $validator->checkArticle($id);

        $user = AccountLoginTokenService::user();

        $this->checkFavoriteLimit($user);

        $favorite = ArticleFavorite::query()->where('article_id', $article->id)->where('user_id', $user->id)->first();

        $isFirstTime = true;

        if (!$favorite) {

            $articleFavoriteData = [
                'article_id' => $article->id,
                'user_id' => $user->id,
            ];

            $favorite = ArticleFavorite::query()->create($articleFavoriteData);

        } else {

            $isFirstTime = false;

            $favorite->delete_time = is_null($favorite->delete_time) ? now() : null;

            $favorite->save();
        }

        if (is_null($favorite->delete_time)) {

            $action = 'do';

            $this->incrArticleFavoriteCount($article);
            $this->incrUserFavoriteCount($user);

            ArticleAfterFavoriteEvent::dispatch($article);

        } else {

            $action = 'undo';

            $this->decrArticleFavoriteCount($article);
            $this->decrUserFavoriteCount($user);
            ArticleAfterUndoFavoriteEvent::dispatch($article);
        }

        $isOwner = $user->id == $article->user_id;

        /**
         * 仅首次收藏发送通知
         */
        if ($isFirstTime && !$isOwner) {
            $this->handleFavoriteNotice($article, $user);
        }

        return [
            'action' => $action,
            'count' => $article->favorite_count,
        ];
    }

    protected function incrArticleFavoriteCount(Article $article)
    {
        $article->favorite_count += 1;

        $article->save();
    }

    protected function decrArticleFavoriteCount(Article $article)
    {
        if ($article->favorite_count > 0) {
            $article->favorite_count -= 1;
            $article->save();
        }
    }

    protected function incrUserFavoriteCount(User $user)
    {
        $user->favorite_count += 1;

        $user->save();
    }

    protected function decrUserFavoriteCount(User $user)
    {
        if ($user->favorite_count > 0) {
            $user->favorite_count -= 1;
            $user->update();
        }
    }

    protected function handleFavoriteNotice(Article $article, User $sender)
    {
        $notice = new ArticleFavorited();

        $notice->handle($article, $sender);
    }

}
