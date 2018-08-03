<?php

return [
    // 类型注册表
    'types' => [
        'graph' => [
            'query' => \app\http\graph\QueryType::class
        ],
        'user' => [
            'query' => \app\http\graph\User\UserType::class
        ]
    ],
    // 入口类型
    'schema' => [
        'graph',
        'blog' => 'graph'
    ],
    // 中间件
    'middleware' => [],
    // 路由前缀
    'routePrefix' => 'api/'
];
