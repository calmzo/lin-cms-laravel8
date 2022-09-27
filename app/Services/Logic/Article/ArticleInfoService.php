<?php

namespace App\Services\Logic\Article;

use App\Caches\CategoryCache;
use App\Events\ArticleAfterViewEvent;
use App\Lib\Validators\ArticleValidator;
use App\Models\Article;
use App\Models\User;
use App\Models\ArticleLike;
use App\Models\ArticleFavorite;
use App\Services\Token\AccountLoginTokenService;
use App\Traits\UserTrait;

class ArticleInfoService
{

    use UserTrait;

    public function handle($id)
    {
        $user = AccountLoginTokenService::user();
        $validator = new ArticleValidator();
        $article = $validator->checkArticle($id);

        $result = $this->handleArticle($article, $user);

        $this->incrArticleViewCount($article);

        ArticleAfterViewEvent::dispatch($article);
        return $result;
    }

    protected function handleArticle(Article $article, User $user)
    {
        $category = $this->handleCategoryInfo($article->category_id);
        $owner = $this->handleShallowUserInfo($article->owner_id);
        $me = $this->handleMeInfo($article, $user);

        return [
            'id' => $article->id,
            'title' => $article->title,
            'cover' => $article->cover,
            'summary' => $article->summary,
            'tags' => $article->tags,
            'content' => $article->content,
            'private' => $article->private,
            'closed' => $article->closed,
            'published' => $article->published,
            'deleted' => $article->deleted,
            'source_type' => $article->source_type,
            'source_url' => $article->source_url,
            'word_count' => $article->word_count,
            'view_count' => $article->view_count,
            'like_count' => $article->like_count,
            'comment_count' => $article->comment_count,
            'favorite_count' => $article->favorite_count,
            'create_time' => $article->create_time,
            'update_time' => $article->update_time,
            'category' => $category,
            'owner' => $owner,
            'me' => $me,
        ];
    }

    protected function handleCategoryInfo($categoryId)
    {
        $cache = new CategoryCache();

        /**
         *
         */
        $category = $cache->get($categoryId);

        if (!$category) return new \stdClass();

        return [
            'id' => $category->id,
            'name' => $category->name,
        ];
    }

    protected function handleMeInfo(Article $article, User $user)
    {
        $me = [
            'liked' => 0,
            'favorited' => 0,
            'owned' => 0,
        ];

        if ($user->id == $article->user_id) {
            $me['owned'] = 1;
        }

        if ($user->id > 0) {

            $like = ArticleLike::query()->where('article_id', $article->id)->where('user_id', $user->id)->first();

            if ($like && $like->deleted == 0) {
                $me['liked'] = 1;
            }

            $favorite = ArticleFavorite::query()->where('article_id', $article->id)->where('user_id', $user->id)->first();

            if ($favorite && $favorite->deleted == 0) {
                $me['favorited'] = 1;
            }
        }

        return $me;
    }

    protected function incrArticleViewCount(Article $article)
    {
        $article->view_count += 1;

        $article->save();
    }

}
