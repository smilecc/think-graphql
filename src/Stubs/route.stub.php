<?php

$graphConfig = config('graph.');

Route::any($graphConfig['routePrefix'] . ':action','\smilecc\think\GraphQLController@action')
    ->middleware(\smilecc\think\GraphQLMiddleware::class);
