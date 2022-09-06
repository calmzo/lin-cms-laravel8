<?php

declare(strict_types=1);

return [
    'oa' => [
        'app_id' =>  env('WECHAT_OA_APP_ID') ?? '',
        'app_secret' =>  env('WECHAT_OA_APP_SECRET') ?? '',
        'app_token' =>  env('WECHAT_OA_APP_TOKEN') ?? '',
        'aes_key' =>  env('WECHAT_OA_AES_KEY') ?? '',
        'enabled' => 1,
        //通知模板
        'notice_template' => [
            //账号登录
            'account_login' => [
                'enabled' => 1,
                'id' => 1234
            ],
            //订单完成
            'order_finish' => [
                'enabled' => 1,
                'id' => 1234
            ],
            //退款
            'refund_finish' => [
                'enabled' => 1,
                'id' => 1234
            ],
            //咨询回复模板
            'consult_reply' => [
                'enabled' => 1,
                'id' => 1234
            ],
        ],


    ],

];
