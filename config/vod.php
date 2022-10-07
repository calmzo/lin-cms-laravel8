<?php

return [
    'secret' => [
        'secret_id' => env('VOD_SECRET_ID'),
        'secret_key' => env('VOD_SECRET_KEY'),
    ],

    'storage_region' => 'ap-guangzhou',
    'storage_type' => 'nearby',
    'video_format' => 'hls',
    'audio_format' => 'mp3',
    'wmk_enabled' => '1',
    'wmk_tpl_id' => '569493',
    'protocol' => 'https',
    'domain' => '',
    'key_anti_key' => '',
    'key_anti_expiry' => '10800',
    'key_anti_ip_limit' => '',
    'key_anti_enabled' => '1',
    'video_quality' => '["hd","sd","fd"]',
    'audio_quality' => '["sd"]',

];
