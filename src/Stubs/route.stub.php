<?php

// 获取配置
$graphConfig = config('graph.');

// 处理中间件
$middleware = [
    \smilecc\think\GraphQLMiddleware::class
];
// 处理配置中的中间件
if (array_key_exists('middleware', $graphConfig)) {
    if (is_array($graphConfig['middleware'])) {
        // 若是数组 则合并进来
        $middleware = array_merge($middleware, $graphConfig['middleware']);
    } else if (!empty($graphConfig['middleware'])) {
        // 若是其他类型 则新增子元素
        $middleware[] = $graphConfig['middleware'];
    }
}

Route::any($graphConfig['routePrefix'] . ':action','\smilecc\think\GraphQLController@action')
    ->middleware($middleware);
