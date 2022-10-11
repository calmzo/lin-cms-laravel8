<?php

namespace App\Validators;

use App\Caches\MaxPackageIdCache;
use App\Caches\PackageCache;
use App\Exceptions\BadRequestException;
use App\Lib\Validators\CommonValidator;
use App\Models\Package;
use App\Repositories\PackageRepository;
use App\Utils\CodeResponse;

class PackageValidator extends BaseValidator
{

    /**
     * @param int $id
     * @return Package
     * @throws BadRequestException
     */
    public function checkPackageCache($id)
    {
        $this->checkId($id);

        $packageCache = new PackageCache();

        $package = $packageCache->get($id);

        if (!$package) {
            throw new BadRequestException(CodeResponse::NOT_FOUND_EXCEPTION, 'package.not_found');
        }

        return $package;
    }

    public function checkPackage($id)
    {
        $this->checkId($id);

        $packageRepo = new PackageRepository();

        $package = $packageRepo->findById($id);

        if (!$package) {
            throw new BadRequestException(CodeResponse::NOT_FOUND_EXCEPTION, 'package.not_found');
        }

        return $package;
    }

    public function checkId($id)
    {
        $id = intval($id);

        $maxIdCache = new MaxPackageIdCache();

        $maxId = $maxIdCache->get();

        if ($id < 1 || $id > $maxId) {
            throw new BadRequestException(CodeResponse::NOT_FOUND_EXCEPTION, 'package.not_found');
        }
    }

    public function checkCover($cover)
    {
        $value = $this->filter->sanitize($cover, ['trim', 'string']);

        if (!CommonValidator::url($value)) {
            throw new BadRequestException(CodeResponse::NOT_FOUND_EXCEPTION, 'package.invalid_cover');
        }

        return kg_cos_img_style_trim($value);
    }

    public function checkTitle($title)
    {
        $value = $this->filter->sanitize($title, ['trim', 'string']);

        $length = kg_strlen($value);

        if ($length < 2) {
            throw new BadRequestException(CodeResponse::NOT_FOUND_EXCEPTION, 'package.title_too_short');
        }

        if ($length > 50) {
            throw new BadRequestException(CodeResponse::NOT_FOUND_EXCEPTION, 'package.title_too_long');
        }

        return $value;
    }

    public function checkSummary($summary)
    {
        $value = $this->filter->sanitize($summary, ['trim', 'string']);

        $length = kg_strlen($value);

        if ($length > 255) {
            throw new BadRequestException(CodeResponse::NOT_FOUND_EXCEPTION, 'package.summary_too_long');
        }

        return $value;
    }

    public function checkMarketPrice($price)
    {
        $value = $this->filter->sanitize($price, ['trim', 'float']);

        if ($value < 0.01 || $value > 10000) {
            throw new BadRequestException(CodeResponse::NOT_FOUND_EXCEPTION, 'package.invalid_market_price');
        }

        return $value;
    }

    public function checkVipPrice($price)
    {
        $value = $this->filter->sanitize($price, ['trim', 'float']);

        if ($value < 0.01 || $value > 10000) {
            throw new BadRequestException(CodeResponse::NOT_FOUND_EXCEPTION, 'package.invalid_vip_price');
        }

        return $value;
    }

    public function checkPublishStatus($status)
    {
        if (!in_array($status, [0, 1])) {
            throw new BadRequestException(CodeResponse::NOT_FOUND_EXCEPTION, 'package.invalid_publish_status');
        }

        return $status;
    }

}
