<?php

declare(strict_types=1);

return [
    'robot' => [
        'enabled' => 1,
        'app_secret' => env('DINGTALK_ROBOT_APP_SECRET') ?? '',
        'app_token' => env('DINGTALK_ROBOT_APP_TOKEN') ?? '',
        'ts_mobiles' => '13153187435,13153187434'
    ]
];
