<?php

return [
    'types' => [
        'graph' => [
            'query' => \app\http\graph\QueryType::class
        ],
        'user' => [
            'query' => \app\http\graph\User\UserType::class
        ]
    ],
    'schema' => [
        'graph'
    ],
    'routePrefix' => 'api/'
];
