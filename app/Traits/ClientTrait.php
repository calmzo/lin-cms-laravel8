<?php

namespace App\Traits;

use App\Enums\ClientEnums;
use App\Exceptions\BadRequestException;
use App\Utils\CodeResponse;
use hisorange\BrowserDetect\Parser;
use Browser;

trait ClientTrait
{

    /**
     * 获取客户端ip
     * @return mixed|string|null
     */
    public function getClientIp()
    {
        return request()->ip();
    }

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

    /**
     * 获取客户端类型
     * @return int|mixed|string
     */
    public function getClientType()
    {

        $request = request();
        $platform = $request->header('X-Platform');
        $types = array_flip(ClientEnums::types());

        if (!empty($platform) && isset($types[$platform])) {
            return $types[$platform];
        }
        $clientType = ClientEnums::TYPE_PC;
        // Determine the user's device type is simple as this:
        if (Browser::isMobile()) {
            $clientType = ClientEnums::TYPE_H5;
        }

        return $clientType;
    }
}
