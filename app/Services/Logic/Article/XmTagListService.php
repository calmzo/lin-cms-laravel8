<?php

namespace App\Services\Logic\Article;

use App\Enums\TagEnums;
use App\Models\Article;
use App\Models\Tag;
use App\Repositories\ArticleRepository;
use App\Repositories\TagRepository;
use App\Services\Logic\LogicService;

class XmTagListService extends LogicService
{

    public function handle($id)
    {

        $tagRepo = new TagRepository();

        $allTags = $tagRepo->findAll(['published' => 1]);
        if ($allTags->count() == 0) return [];

        $articleTagIds = [];

        if ($id > 0) {
            $article = $this->findArticle($id);
            if (!empty($article->tags)) {
                $articleTagIds = array_column_unique($article->tags, 'id');
            }
        }

        $list = [];

        foreach ($allTags as $tag) {
            $case1 = is_string($tag->scopes) && $tag->scopes == 'all';
            $case2 = is_array($tag->scopes) && in_array(TagEnums::SCOPE_ARTICLE, $tag->scopes);
            if ($case1 || $case2) {
                $selected = in_array($tag->id, $articleTagIds);
                $list[] = [
                    'name' => $tag->name,
                    'value' => $tag->id,
                    'selected' => $selected,
                ];
            }
        }

        return $list;
    }

    protected function findArticle($id)
    {
        $articleRepo = new ArticleRepository();

        return $articleRepo->findById($id);
    }
}
