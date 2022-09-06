<?php

declare(strict_types=1);

return [
    'oa' => [
        'app_id' =>  env('WECHAT_OA_APP_ID') ?? '',
        'app_secret' =>  env('WECHAT_OA_APP_SECRET') ?? '',
        'app_token' =>  env('WECHAT_OA_APP_TOKEN') ?? '',
        'aes_key' =>  env('WECHAT_OA_AES_KEY') ?? '',

        //开关
        'enabled' => 1,
        //通知模板
        'notice_template' => [
            //账号登录
            'account_login' => [
                //开关
                'enabled' => 1,
                //模板id
                'id' => 1234
            ],
        ]
    ],

];
