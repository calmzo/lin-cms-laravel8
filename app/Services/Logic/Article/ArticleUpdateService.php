<?php


namespace App\Services\Logic\Article;

use App\Enums\ArticleEnums;
use App\Events\ArticleAfterUpdateEvent;
use App\Validators\ArticleValidator;
use App\Services\Logic\LogicService;
use App\Traits\ArticleDataTrait;

class ArticleUpdateService extends LogicService
{

    use ArticleDataTrait;

    public function handle($id, $params)
    {
        $validator = new ArticleValidator();
        $article = $validator->checkArticle($id);

        $validator->checkIfAllowEdit($article);
        $data = $this->handleParamsData($params);

        if ($article->published == ArticleEnums::PUBLISH_REJECTED) {
            $data['published'] = ArticleEnums::PUBLISH_PENDING;
        }

        $article->update($data);

        if (isset($params['xm_tag_ids'])) {
            $this->saveTags($article, $params['xm_tag_ids']);
        }

        $this->saveDynamicAttrs($article);
        ArticleAfterUpdateEvent::dispatch($article);

        return $article;
    }

}
