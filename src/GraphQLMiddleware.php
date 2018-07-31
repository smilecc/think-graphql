<?php

namespace smilecc\think;

class GraphQLMiddleware
{
    public function handle($request, \Closure $next)
    {
        return $next($request);
    }
}
