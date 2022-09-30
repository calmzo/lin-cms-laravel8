<?php

namespace App\Services\Logic\Article;

use App\Lib\Validators\ArticleValidator;
use App\Services\Logic\LogicService;
use App\Services\Token\AccountLoginTokenService;

class ArticleCloseService extends LogicService
{

    public function handle($id)
    {
        $validator = new ArticleValidator();
        $article = $validator->checkArticle($id);

        $user = AccountLoginTokenService::user();
        $validator->checkOwner($user->id, $article->user_id);

        $article->closed = $article->closed == 1 ? 0 : 1;

        $article->save();

        return $article;
    }

}
