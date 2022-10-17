<?php

declare(strict_types=1);

return [
    //腾讯云短信
    'app_id' => env('SMS_TENCENT_APP_ID') ?? '',
    'signature' => env('SMS_TENCENT_SIGNATURE') ?? '',
    'region' => env('SMS_TENCENT_REGION') ?? '',
    'endpoint' => env('SMS_TENCENT_ENDPOINT') ?? '', //请求接入点域名
    'template' => [
        'verify' => [
            'id' => 111111,
            'enabled' => 1
        ],
        'order_finish' => [
            'id' => 111111,
            'enabled' => 1
        ],
        'refund_finish' => [
            'id' => 222222,
            'enabled' => 1
        ],
        //咨询回复模板
        'consult_reply' => [
            'enabled' => 1,
            'id' => 1234
        ],
        'live_begin' => [
            'enabled' => 1,
            'id' => 12345
        ],

        'goods_deliver' => [
            'enabled' => 1,
            'id' => 123456
        ],

    ]
];
