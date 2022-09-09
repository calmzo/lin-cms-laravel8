<?php

declare(strict_types=1);

return [
    'ping_interval' => 30, //客户端ping服务端间隔（秒）
    'connect_address' => 'your_domain.com:8282',  //客户端连接地址（外部可访问的域名或ip），带端口号
    'register_address' => '127.0.0.1:1238' //gateway和worker注册地址，带端口号
];
