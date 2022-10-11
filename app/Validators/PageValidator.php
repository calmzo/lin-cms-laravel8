<?php

namespace App\Validators;

use App\Caches\MaxPageIdCache;
use App\Caches\PageCache;
use App\Exceptions\BadRequestException;
use App\Lib\Validators\CommonValidator;
use App\Models\Page;
use App\Repositories\PageRepository;
use App\Utils\CodeResponse;

class PageValidator extends BaseValidator
{

    /**
     * @param int $id
     * @return Page
     * @throws BadRequestException
     */
    public function checkPageCache($id)
    {
        $this->checkId($id);

        $pageCache = new PageCache();

        $page = $pageCache->get($id);

        if (!$page) {
            throw new BadRequestException(CodeResponse::NOT_FOUND_EXCEPTION, 'page.not_found');
        }

        return $page;
    }

    public function checkPage($id)
    {
        $pageRepo = new PageRepository();

        if (CommonValidator::intNumber($id)) {
            $page = $pageRepo->findById($id);
        } else {
            $page = $pageRepo->findByAlias($id);
        }

        if (!$page) {
            throw new BadRequestException(CodeResponse::NOT_FOUND_EXCEPTION, 'page.not_found');
        }

        return $page;
    }

    public function checkId($id)
    {
        $id = intval($id);

        $maxIdCache = new MaxPageIdCache();

        $maxId = $maxIdCache->get();

        if ($id < 1 || $id > $maxId) {
            throw new BadRequestException(CodeResponse::NOT_FOUND_EXCEPTION, 'page.not_found');
        }
    }

    public function checkTitle($title)
    {
        $value = $this->filter->sanitize($title, ['trim', 'string']);

        $length = kg_strlen($value);

        if ($length < 2) {
            throw new BadRequestException(CodeResponse::NOT_FOUND_EXCEPTION, 'page.title_too_short');
        }

        if ($length > 50) {
            throw new BadRequestException(CodeResponse::NOT_FOUND_EXCEPTION, 'page.title_too_long');
        }

        return $value;
    }

    public function checkAlias($alias)
    {
        $value = $this->filter->sanitize($alias, ['trim', 'string']);

        $value = str_replace(['/', '?', '#'], '', $value);

        $length = kg_strlen($value);

        if (CommonValidator::intNumber($value)) {
            throw new BadRequestException(CodeResponse::NOT_FOUND_EXCEPTION, 'page.invalid_alias');
        }

        if ($length < 2) {
            throw new BadRequestException(CodeResponse::NOT_FOUND_EXCEPTION, 'page.alias_too_short');
        }

        if ($length > 50) {
            throw new BadRequestException(CodeResponse::NOT_FOUND_EXCEPTION, 'page.alias_too_long');
        }

        return $value;
    }

    public function checkContent($content)
    {
        $value = $this->filter->sanitize($content, ['trim']);

        $length = kg_strlen($value);

        if ($length < 10) {
            throw new BadRequestException(CodeResponse::NOT_FOUND_EXCEPTION, 'page.content_too_short');
        }

        if ($length > 30000) {
            throw new BadRequestException(CodeResponse::NOT_FOUND_EXCEPTION, 'page.content_too_long');
        }

        return kg_clean_html($value);
    }

    public function checkPublishStatus($status)
    {
        if (!in_array($status, [0, 1])) {
            throw new BadRequestException(CodeResponse::NOT_FOUND_EXCEPTION, 'page.invalid_publish_status');
        }

        return $status;
    }

    public function checkIfAliasTaken($alias)
    {
        $pageRepo = new PageRepository();

        $page = $pageRepo->findByAlias($alias);

        if ($page) {
            throw new BadRequestException(CodeResponse::NOT_FOUND_EXCEPTION, 'page.alias_taken');
        }
    }

}
