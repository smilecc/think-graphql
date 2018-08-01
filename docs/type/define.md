# 自定义类型

自定义类型可以理解为一种复合类型。

## 定义方式

只要继承于 `smilecc\think\Support\ObjectType` 即可轻松定义一个自定义类型 。

```php
<?php
namespace app\http\graph\User;

use \smilecc\think\Support\Types;
use \smilecc\think\Support\ObjectType;

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
            'nickname' => Types::string()
        ];
    }
}
```

定义类型之后只需要在 `/config/graph.php` 的 `types` 中注册该类型的class即可。

```php
<?php

return [
    'types' => [
        'user' => \app\http\graph\User\UserType::class
    ]
];
```

随后就可以通过**类型注册表**来使用它了。

```php
<?php
namespace app\http\graph;

use \smilecc\think\Support\Types;
use \smilecc\think\Support\ObjectType;

class GraphType extends ObjectType
{
    public function attrs()
    {
        return [
            'name' => 'GraphType',
            'desc' => ''
        ];
    }

    public function fields()
    {
        return [
            // 刚才注册的UserType
            'user' => Types::user()
        ];
    }
}
```


## ObjectType

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

在这个例子中我们就把上层传递过来的数据 `$val` 中的 `created_time` 转换成时间戳再返回。

