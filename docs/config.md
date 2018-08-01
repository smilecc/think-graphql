# 配置


配置文件位于`/config/graph.php`。

默认为如下内容：

```php
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
```

配置项 | 说明
--- | ---
types | 所有GraphQL的Type
schema | 定义入口Type
routePrefix | 路由前缀

### types

用于定义你在项目中所使用的Types，例如
```php
'types' => [
    'user' => [
        'query' => \app\http\graph\UserType::class
        'mutation' => \app\http\graph\UserMutationType::class
    ]
]
```
此时在此定义`user`之后，我们就可以使用`Types::user()`获取这个Type，例如。

```php
<?php
namespace app\http\graph;

use \smilecc\think\Support\Types;
use \smilecc\think\Support\ObjectType;

class QueryType extends ObjectType
{
    public function fields()
    {
        return [
            'user' => Types::user()
        ];
    }
}
```

在默认情况下调用`Types::user()`会自动实例化配置中`query`所对应的class，而如果需要实例化`mutation`的话，那么只需要调用`Types::user('mutation')`即可。

### schema

`schema`是定义入口type的数组，例如默认内容中我们定义了：
```php
'schema' => [
    'graph'
]
```
这时我们的`http://example.com/api/graph`就会变成一个入口，我们在设置将这个地址`endpoint`，就可以将它作为请求的入口了。

所以我们可以有多个入口，例如
```php
<?php

return [
    'types' => [
        'user' => [
            'query' => \app\http\graph\User\UserType::class
        ],
        'admin' => [
            'query' => \app\http\graph\Admin\AdminType::class
        ]
    ],
    'schema' => [
        'user',
        'admin'
    ],
    'routePrefix' => 'api/'
];
```
这样我们就会拥有 `/api/user` 和 `/api/admin` 两个入口来处理不同的业务了。

### routePrefix

`routePrefix` 是路由的前缀，默认的配置中使用的是 `api/` ，此时我们使用 `api/user` 来作为入口，如果我们不喜欢 `api/` 可以将其自定义，例如将其修改为 `v1/` 后，那我们我们的入口就会变成 `/v1/user`。
