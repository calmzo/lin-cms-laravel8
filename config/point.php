<?php

declare(strict_types=1);

return [
    'enabled' => true,
    'consume_rule' => [
        'enabled' => 1,
        'rate' => 0.1
    ],
    'event_rule' => [
        'account_register' => [
            'enabled' => 1,
            'point' => 100
        ],
        'article_liked' => [
            'enabled' => 1,
            'point' => 100,
            'limit' => 1
        ],


    ],
];
