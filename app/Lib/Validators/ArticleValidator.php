<?php

namespace App\Lib\Validators;

use App\Caches\Article as ArticleCache;
use App\Caches\MaxArticleIdCache;
use App\Enums\ArticleEnums;
use App\Exceptions\BadRequestException;
use App\Models\Article;
use App\Utils\CodeResponse;

class ArticleValidator extends BaseValidator
{

    /**
     * @param int $id
     * @return ArticleModel
     * @throws BadRequestException
     */
    public function checkArticleCache($id)
    {
        $this->checkId($id);

        $articleCache = new ArticleCache();

        $article = $articleCache->get($id);

        if (!$article) {
            throw new BadRequestException('article.not_found');
        }

        return $article;
    }

    public function checkArticle($id)
    {
        $this->checkId($id);

        $article = Article::query()->find($id);

        if (!$article) {
            throw new BadRequestException(CodeResponse::NOT_FOUND_EXCEPTION, 'article.not_found');
        }

        return $article;
    }

    public function checkId($id)
    {
        $id = intval($id);

        $maxIdCache = new MaxArticleIdCache();

        $maxId = $maxIdCache->get();

        if ($id < 1 || $id > $maxId) {
            throw new BadRequestException(CodeResponse::NOT_FOUND_EXCEPTION, 'article.not_found');
        }
    }

    public function checkCategory($id)
    {
        $validator = new CategoryValidator();

        return $validator->checkCategory($id);
    }

    public function checkTitle($title)
    {
        $value = $this->filter->sanitize($title, ['trim', 'string']);

        $length = kg_strlen($value);

        if ($length < 2) {
            throw new BadRequestException('article.title_too_short');
        }

        if ($length > 50) {
            throw new BadRequestException('article.title_too_long');
        }

        return $value;
    }

    public function checkContent($content)
    {
        $value = $this->filter->sanitize($content, ['trim']);

        $length = kg_strlen($value);

        if ($length < 10) {
            throw new BadRequestException('article.content_too_short');
        }

        if ($length > 30000) {
            throw new BadRequestException('article.content_too_long');
        }

        return kg_clean_html($value);
    }

    public function checkSourceType($type)
    {
        if (!array_key_exists($type, ArticleModel::sourceTypes())) {
            throw new BadRequestException('article.invalid_source_type');
        }

        return (int)$type;
    }

    public function checkSourceUrl($url)
    {
        if (!CommonValidator::url($url)) {
            throw new BadRequestException(CodeResponse::NOT_FOUND_EXCEPTION, 'article.invalid_source_url');
        }

        return $url;
    }

    public function checkFeatureStatus($status)
    {
        if (!in_array($status, [0, 1])) {
            throw new BadRequestException('article.invalid_feature_status');
        }

        return $status;
    }

    public function checkPublishStatus($status)
    {
        if (!array_key_exists($status, ArticleModel::publishTypes())) {
            throw new BadRequestException('article.invalid_publish_status');
        }

        return $status;
    }

    public function checkPrivateStatus($status)
    {
        if (!in_array($status, [0, 1])) {
            throw new BadRequestException('article.invalid_private_status');
        }

        return $status;
    }

    public function checkCloseStatus($status)
    {
        if (!in_array($status, [0, 1])) {
            throw new BadRequestException('article.invalid_close_status');
        }

        return $status;
    }

    public function checkRejectReason($reason)
    {
        if (!array_key_exists($reason, ReasonModel::questionRejectOptions())) {
            throw new BadRequestException('article.invalid_reject_reason');
        }
    }

    public function checkIfAllowEdit(Article $article)
    {
        $approved = $article->published == ArticleEnums::PUBLISH_APPROVED;

        if ($approved) {
            throw new BadRequestException(CodeResponse::NOT_FOUND_EXCEPTION, 'article.edit_not_allowed');
        }
    }

}
