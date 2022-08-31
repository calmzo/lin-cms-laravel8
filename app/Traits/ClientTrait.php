<?php

namespace App\Traits;

use App\Enums\ClientEnums;
use App\Exceptions\BadRequestException;
use App\Utils\CodeResponse;

trait ClientTrait
{
    public function checkH5Platform($platform)
    {
        $platform = strtoupper($platform);

        if ($platform == 'H5') {
            return ClientEnums::TYPE_H5;
        }

        throw new BadRequestException(CodeResponse::NOT_FOUND_EXCEPTION, '无效的终端类型');
    }

    public function checkMpPlatform($platform)
    {
        $platform = strtoupper($platform);

        if ($platform == 'MP-WEIXIN') {
            return ClientEnums::TYPE_MP_WEIXIN;
        } elseif ($platform == 'MP-ALIPAY') {
            return ClientEnums::TYPE_MP_ALIPAY;
        } else {
            throw new BadRequestException(CodeResponse::NOT_FOUND_EXCEPTION, '无效的终端类型');
        }
    }

    public function checkAppPlatform($platform)
    {
        $platform = strtoupper($platform);

        if ($platform == 'APP-PLUS') {
            return ClientEnums::TYPE_APP;
        }

        throw new BadRequestException(CodeResponse::NOT_FOUND_EXCEPTION, '无效的终端类型');
    }

}
