<?php

namespace App\Services\Logic\Article;

use App\Enums\TagEnums;
use App\Models\Article;
use App\Models\Tag;
use App\Services\Logic\LogicService;

class XmTagListService extends LogicService
{

    public function handle($id)
    {

        $allTags = Tag::query()->where('published', 1)->get();
        if ($allTags->count() == 0) return [];

        $articleTagIds = [];

        if ($id > 0) {
            $article = Article::query()->find($id);
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
}
