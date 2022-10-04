<?php

namespace App\Services\Logic\Article;

use App\Validators\ArticleValidator;
use App\Services\Logic\LogicService;
use App\Services\Token\AccountLoginTokenService;

class ArticlePrivateService extends LogicService
{

    public function handle($id)
    {
        $validator = new ArticleValidator();
        $article = $validator->checkArticle($id);
        $user = AccountLoginTokenService::user();
        $validator->checkOwner($user->id, $article->user_id);

        $article->private = $article->private == 1 ? 0 : 1;

        $article->save();

        return $article;
    }

}
