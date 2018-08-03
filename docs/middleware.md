# 中间件

`think-graphql` 的中间件用于在请求时处理或拦截用户请求，`think-graphql` 的中间件只对GraphQL的接口生效。

## 定义中间件

定义中间件的方法参见 [ThinkPHP的中间件](https://www.kancloud.cn/manual/thinkphp5_1/564279)。

所使用的中间件类的示例如下。

```php
<?php

namespace app\http\middleware;

class Check
{
    public function handle($request, \Closure $next)
    {
        if ($request->param('name') == 'think') {
            return redirect('index/think');
        }

        return $next($request);
    }
}
```

## 注册中间件

在 `/config/graph.php` 中找到 `middleware` 配置数组，将你定义的中间件加入即可。

```php
'middleware' => [
    app\http\middleware\Check::class
]
```
或
```php
'middleware' => app\http\middleware\Check::class
```
