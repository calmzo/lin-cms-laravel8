<?php

namespace App\Validators;

use App\Exceptions\BadRequestException;
use App\Repositories\VipRepository;
use App\Utils\CodeResponse;

class VipValidator extends BaseValidator
{

    public function checkVip($id)
    {
        $vipRepo = new VipRepository();

        $vip = $vipRepo->findById($id);

        if (!$vip) {
            throw new BadRequestException(CodeResponse::NOT_FOUND_EXCEPTION, 'vip.not_found');
        }

        return $vip;
    }

    public function checkTitle($title)
    {
        $value = $this->filter->sanitize($title, ['trim', 'string']);

        $length = kg_strlen($value);

        if ($length < 2) {
            throw new BadRequestException(CodeResponse::NOT_FOUND_EXCEPTION, 'vip.title_too_short');
        }

        if ($length > 30) {
            throw new BadRequestException(CodeResponse::NOT_FOUND_EXCEPTION, 'vip.title_too_long');
        }

        return $value;
    }

    public function checkExpiry($expiry)
    {
        $value = $this->filter->sanitize($expiry, ['trim', 'int']);

        if ($value < 1 || $value > 60) {
            throw new BadRequestException(CodeResponse::NOT_FOUND_EXCEPTION, 'vip.invalid_expiry');
        }

        return $value;
    }

    public function checkPrice($price)
    {
        $value = $this->filter->sanitize($price, ['trim', 'float']);

        if ($value < 0.01 || $value > 10000) {
            throw new BadRequestException(CodeResponse::NOT_FOUND_EXCEPTION, 'vip.invalid_price');
        }

        return $value;
    }

}
