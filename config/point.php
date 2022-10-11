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
            'point' => 100,
        ],
        'article_liked' => [
            'enabled' => 1,
            'point' => 200,
            'limit' => 3000
        ],
        'site_visit' => [
            'enabled' => 1,
            'point' => 300,
            'limit' => 3000
        ],
        'course_review' => [
            'enabled' => 1,
            'point' => 400,
            'limit' => 3000
        ],

        'question_liked' => [
            'enabled' => 1,
            'point' => 500,
            'limit' => 3000
        ],

        'answer_post' => [
            'enabled' => 1,
            'point' => 600,
            'limit' => 3000
        ],

        'answer_accepted' => [
            'enabled' => 1,
            'point' => 700,
            'limit' => 3000
        ],

        'answer_liked' => [
            'enabled' => 1,
            'point' => 800,
            'limit' => 3000
        ],
        'comment_post' => [
            'enabled' => 1,
            'point' => 900,
            'limit' => 3000
        ],


    ],
];
