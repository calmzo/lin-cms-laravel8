<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class ConnectEnums extends Enum
{
    const ERROR_STR = '未知';

    const PROVIDER_QQ = 1; // QQ
    const PROVIDER_WEIXIN = 2; // 微信扫码
    const PROVIDER_WEIBO = 3; // 新浪微博
    const PROVIDER_WECHAT = 4; // 公众号网页


}
