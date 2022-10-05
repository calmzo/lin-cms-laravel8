<?php

namespace App\Validators;

use App\Caches\HelpCache;
use App\Caches\MaxHelpIdCache;
use App\Exceptions\BadRequestException;
use App\Repositories\HelpRepository;
use App\Utils\CodeResponse;

class HelpValidator extends BaseValidator
{

    /**
     * @param int $id
     * @return Help
     * @throws BadRequestException
     */
    public function checkHelpCache($id)
    {
        $this->checkId($id);

        $helpCache = new HelpCache();

        $help = $helpCache->get($id);

        if (!$help) {
            throw new BadRequestException(CodeResponse::NOT_FOUND_EXCEPTION, 'help.not_found');
        }

        return $help;
    }

    public function checkHelp($id)
    {
        $helpRepo = new HelpRepository();

        $help = $helpRepo->findById($id);

        if (!$help) {
            throw new BadRequestException(CodeResponse::NOT_FOUND_EXCEPTION, 'help.not_found');
        }

        return $help;
    }

    public function checkId($id)
    {
        $id = intval($id);

        $maxIdCache = new MaxHelpIdCache();

        $maxId = $maxIdCache->get();

        if ($id < 1 || $id > $maxId) {
            throw new BadRequestException(CodeResponse::NOT_FOUND_EXCEPTION, 'help.not_found');
        }
    }

    public function checkCategory($id)
    {
        $validator = new Category();

        return $validator->checkCategory($id);
    }

    public function checkTitle($title)
    {
        $value = $this->filter->sanitize($title, ['trim', 'string']);

        $length = kg_strlen($value);

        if ($length < 2) {
            throw new BadRequestException('help.title_too_short');
        }

        if ($length > 50) {
            throw new BadRequestException('help.title_too_long');
        }

        return $value;
    }

    public function checkContent($content)
    {
        $value = $this->filter->sanitize($content, ['trim']);

        $length = kg_strlen($value);

        if ($length < 10) {
            throw new BadRequestException('help.content_too_short');
        }

        if ($length > 30000) {
            throw new BadRequestException('help.content_too_long');
        }

        return kg_clean_html($value);
    }

    public function checkPriority($priority)
    {
        $value = $this->filter->sanitize($priority, ['trim', 'int']);

        if ($value < 1 || $value > 255) {
            throw new BadRequestException('help.invalid_priority');
        }

        return $value;
    }

    public function checkPublishStatus($status)
    {
        if (!in_array($status, [0, 1])) {
            throw new BadRequestException(CodeResponse::NOT_FOUND_EXCEPTION, 'help.invalid_publish_status');
        }

        return $status;
    }

}
