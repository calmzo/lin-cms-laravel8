<?php

namespace App\Traits;

use App\Enums\ArticleEnums;
use App\Services\Token\AccountLoginTokenService;
use App\Utils\Word;
use App\Models\Article;
use App\Models\ArticleTag;
use App\Models\Tag;
use App\Models\User;
use App\Services\ArticleTagService;
use App\Services\TagService;
use App\Validators\ArticleValidator;

trait ArticleDataTrait
{

    use ClientTrait;

    protected function handleParamsData($params)
    {
        $data = [];

        $data['title'] = $params['title'] ?? '';
        $data['content'] = $params['content'] ?? '';
        $data['source_type'] = $params['source_type'] ?? '';
        $data['source_url'] = $params['source_url'] ?? '';
        $data['closed'] = $params['closed'] ?? '';
        $data['private'] = $params['private'] ?? '';
        $data['client_type'] = $this->getClientType();
        $data['client_ip'] = $this->getClientIp();

        $validator = new ArticleValidator();

        if (isset($params['category_id'])) {
            $category = $validator->checkCategory($params['category_id']);
            $data['category_id'] = $category->id;
        }
        if (isset($params['source_type'])) {
            if ($params['source_type'] != ArticleEnums::SOURCE_ORIGIN) {
                $data['source_url'] = $validator->checkSourceUrl($params['source_url']);
            }
        }
        $user = AccountLoginTokenService::user();
        $data['published'] = $this->getPublishStatus($user);
        $data['user_id'] = $user['id'];
        $data['closed'] = $params['closed'];
        $data['private'] = $params['private'];
        return $data;
    }


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
            $originTagIds = array_column_unique($article->tags, 'id');
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
