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
这样我们就会拥有`/api/user`和`/api/admin`两个入口来处理不同的业务了。

### routePrefix

`routePrefix`是路由的前缀，默认的配置中使用的是`api/`，此时我们使用`api/user`来作为入口，如果我们不喜欢`api/`可以将其自定义，例如将其修改为`v1/`后，那我们我们的入口就会变成`/v1/user`。

## 定义Type

### ObjectType

```php
<?php
namespace app\http\graph;

use \smilecc\think\Support\Types;
use \smilecc\think\Support\ObjectType;

class QueryType extends ObjectType
{
    public function attrs()
    {
        return [
            'name' => 'GraphQueryType',
            'desc' => 'think-graphql的演示类型'
        ];
    }

    public function fields()
    {
        return [
            'hello_world' => Types::user(),
            'user' => [
                'type' => Types::user(),
                'args' => [
                    'id' => Types::nonNull(Types::id())
                ]
            ]
        ];
    }

    public function resolveHelloWorld($val, $args)
    {
        return 'hello world!!!';
    }

    public function resolveUser($val, $args)
    {
        return [
            'id' => $args['id'],
            'nickname' => 'TestUser',
            'created_time' => '2018-07-31 17:21:50'
        ];
    }
}

```

- `attrs` 定义了该Type的名称及描述。
- `fields` 定义了该Type的返回字段。

此例中我们定义了一个名叫`hello_world`的字段，那么只需要定义一个`resolveHelloWorld`方法为他设置一个值。

我们可以看出字段中使用下划线或小驼峰的情况，我们在`resolve`时可以直接使用大驼峰来处理它。

例如`hello_world`->`resolveHelloWorld`、`craeted_time`->`resolveCreatedTime`。

在使用我们的自定义Type的时候，我们只需要返回一个数组既可。

```php
public function resolveUser($val, $args)
{
    return [
        'id' => $args['id'],
        'nickname' => 'TestUser',
        'created_time' => '2018-07-31 17:21:50'
    ];
}
```

在自定义Type的内部，如果我们在外层返回数组中的字段名和自定义Type内部fields中设置的相同，那么无需任何操作，`think-graphql`会自动帮你返回该值。

如果和内部定义的不一致或需要二次处理，那么我们在自定义Type的内部则需要resolve该字段自己处理一下。

```php
class UserType extends ObjectType
{
    public function attrs()
    {
        return [
            'name' => 'UserType',
            'desc' => '用户类型'
        ];
    }

    public function fields()
    {
        return [
            'id' => Types::id(),
            'nickname' => Types::string(),
            'created_time' => Types::string()
        ];
    }

    public function resolveCreatedTime($val)
    {
        return strtotime($val['created_time']);
    }
}
```

在这个例子中我们就把上层传递过来的数据($val)中的`created_time`转换成时间戳再返回。
