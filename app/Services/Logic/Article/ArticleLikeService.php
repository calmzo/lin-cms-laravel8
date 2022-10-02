<?php

namespace App\Services\Logic\Article;


use App\Events\ArticleAfterLikeEvent;
use App\Events\ArticleAfterUndoLikeEvent;
use App\Events\IncrArticleLikeCountEvent;
use App\Lib\Notice\ArticleLiked;
use App\Lib\Validators\ArticleValidator;
use App\Models\Article;
use App\Models\User;
use App\Repositories\ArticleLikeRepository;
use App\Services\Logic\LogicService;
use App\Services\Token\AccountLoginTokenService;
use App\Traits\UserLimitTrait;
use App\Models\ArticleLike;
use App\Services\Logic\Point\History\ArticleLikedService;

class ArticleLikeService extends LogicService
{

    use UserLimitTrait;

    public function handle($id)
    {
        $validator = new ArticleValidator();
        $article = $validator->checkArticle($id);

        $user = AccountLoginTokenService::user();

        $this->checkDailyArticleLikeLimit($user);

        $likeRepo = new ArticleLikeRepository();

        $articleLike = $likeRepo->findArticleLike($article->id, $user->id);
        $isFirstTime = true;

        if (!$articleLike) {

            $articleLike = new ArticleLike();
            $articleLike->article_id = $article->id;
            $articleLike->user_id = $user->id;

            $articleLike->save();

        } else {

            $isFirstTime = false;

            $articleLike->delete_time = is_null($articleLike->delete_time) ? now() : null;

            $articleLike->save();
        }

        $this->incrUserDailyArticleLikeCount($user);


        if (is_null($articleLike->delete_time)) {

            $action = 'do';

            $this->incrArticleLikeCount($article);

            ArticleAfterLikeEvent::dispatch($article);

        } else {

            $action = 'undo';

            $this->decrArticleLikeCount($article);
            ArticleAfterUndoLikeEvent::dispatch($article);
        }

        $isOwner = $user->id == $article->user_id;

        /**
         * 仅首次点赞发送通知和奖励积分
         */
        if ($isFirstTime && !$isOwner) {
            $this->handleArticleLikedNotice($article, $user);
            $this->handleArticleLikedPoint($articleLike);
        }

        return [
            'action' => $action,
            'count' => $article->like_count,
        ];
    }

    protected function incrArticleLikeCount(Article $article)
    {
        $article->like_count += 1;

        $article->save();
    }

    protected function decrArticleLikeCount(Article $article)
    {
        if ($article->like_count > 0) {
            $article->like_count -= 1;
            $article->save();
        }
    }

    protected function incrUserDailyArticleLikeCount(User $user)
    {
        IncrArticleLikeCountEvent::dispatch($user);
    }

    protected function handleArticleLikedNotice(Article $article, User $sender)
    {
        $notice = new ArticleLiked();

        $notice->handle($article, $sender);
    }

    protected function handleArticleLikedPoint(ArticleLike $articleLike)
    {
        $service = new ArticleLikedService();

        $service->handle($articleLike);
    }

}
