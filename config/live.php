<?php

return [
    'secret' => [
        'secret_id' => 'xxx',
        'secret_key' => 'xxx',
    ],
    'push' => [
        'auth_enabled' => 1,
        'auth_key' => '',
        'auth_delta' => '18000',
        'domain' => 'push.ctc.koogua.com'

    ],
    'pull' => [
        'protocol'=> 'http',
        'domain'=> 'play.ctc.koogua.com',
        'auth_enabled'=> '1',
        'trans_enabled'=> '0',
        'auth_key'=> '',
        'auth_delta'=> '18000',
    ],
    'notify' => [

    ]

];
