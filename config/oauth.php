<?php

declare(strict_types=1);

return [
    //第三方
    'qq' => [
        'client_id' => env('OAUTH_QQ_CLIENT_ID') ?? '',
        'client_secret' => env('OAUTH_QQ_CLIENT_SECRET') ?? '',
        'redirect_uri' => env('OAUTH_QQ_REDIRECT_URI') ?? '',
    ],
    'weixin' => [
        'client_id' => env('OAUTH_WX_CLIENT_ID') ?? '',
        'client_secret' => env('OAUTH_WX_CLIENT_SECRET') ?? '',
        'redirect_uri' => env('OAUTH_WX_REDIRECT_URI') ?? '',
    ],
    'weibo' => [
        'client_id' => env('OAUTH_WEIBO_CLIENT_ID') ?? '',
        'client_secret' => env('OAUTH_WEIBO_CLIENT_SECRET') ?? '',
        'redirect_uri' => env('OAUTH_WEIBO_REDIRECT_URI') ?? '',
    ]
];
