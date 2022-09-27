<?php

namespace App\Traits;

use App\Enums\ArticleEnums;
use App\Utils\Word;
use App\Models\Article;
use App\Models\ArticleTag;
use App\Models\Tag;
use App\Models\User;
use App\Services\ArticleTagService;
use App\Services\TagService;
trait ArticleDataTrait
{

    use ClientTrait;

    protected function getPublishStatus(User $user)
    {
        return $user->article_count > 100 ? ArticleEnums::PUBLISH_APPROVED : ArticleEnums::PUBLISH_PENDING;
    }

    protected function saveDynamicAttrs(Article $article)
    {
        $article->cover = kg_parse_first_content_image($article->content);
        $article->summary = kg_parse_summary($article->content);
        $article->word_count = Word::getWordCount($article->content);
        $article->save();
    }

    protected function saveTags(Article $article, $tagIds)
    {
        $originTagIds = [];
        if ($article->tags) {
            $originTagIds = kg_array_column($article->tags, 'id');
        }

        $newTagIds = $tagIds ? explode(',', $tagIds) : [];
        $addedTagIds = array_diff($newTagIds, $originTagIds);

        if ($addedTagIds) {
            foreach ($addedTagIds as $tagId) {
                $articleData = [
                    'article_id' => $article->id,
                    'tag_id' => $tagId,
                ];
                ArticleTag::query()->create($articleData);
                $this->recountTagArticles($tagId);
            }
        }

        $deletedTagIds = array_diff($originTagIds, $newTagIds);

        if ($deletedTagIds) {
            $articleTagService = new ArticleTagService();
            foreach ($deletedTagIds as $tagId) {
                $articleTag = $articleTagService->findArticleTag($article->id, $tagId);
                if ($articleTag) {
                    $articleTag->delete();
                    $this->recountTagArticles($tagId);
                }
            }
        }

        $articleTags = [];

        if ($newTagIds) {
            $tagService = new TagService();
            $tags = $tagService->findByIds($newTagIds);
            if ($tags->count() > 0) {
                $articleTags = [];
                foreach ($tags as $tag) {
                    $articleTags[] = ['id' => $tag->id, 'name' => $tag->name];
                    $this->recountTagArticles($tag->id);
                }
            }
        }

        $article->tags = $articleTags;

        $article->save();
    }

    protected function recountTagArticles($tagId)
    {

        $tag = Tag::query()->find($tagId);

        if (!$tag) return;
        $tagService = new TagService();
        $articleCount = $tagService->countArticles($tagId);

        $tag->article_count = $articleCount;

        $tag->save();
    }

}
