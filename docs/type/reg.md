# 类型注册表

类型注册表起到汇总类型的作用，可以非常方便和灵活的使用内置类型和自定义类型。

## 使用

类 `smilecc\think\Support\Types` 是所有Type的注册表，通过调用该类的静态方法可获取到所有的**内置类型**和**自定义类型**。

```php
<?php

use smilecc\think\Support\Types;

// 内置类型
Types::int();
Types::string();

// 自定义类型
Types::user();
Types::userPayment();
```

## 注册自定义类型

类型注册需要在配置文件中注册，配置文件一般位于`/config/graph.php`。

在配置文件中找到名叫 `types` 的数组，新增一个数组成员。

```php
'types' => [
    'user' => \app\http\graph\UserType::class
]
```

在上面的例子中我们定义了一个名叫 `user` 的Type，定义之后我们就可以在别的Type中用如下的方式使用它。

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

## 定义不同指令对应的Type

如果您不了解 [GraphQL中的指令](http://graphql.cn/learn/queries/#directives) 建议您先阅读一下官方文档。

默认情况下在我们定义 `'user' => \app\http\graph\UserType::class` 之后，我们使用 `Types::user()` 来访问它时，其实是隐式的通过 `query` 的指令访问它，所以 `Types::user()` 等同于 `Types::user('query')` 。

```php
'types' => [
    'user' => [
        'query' => \app\http\graph\UserType::class
        'mutation' => \app\http\graph\UserMutationType::class
    ]
]
```

但如果我们需要访问 `mutation` 指令所对应的类型时，聪明的你看到上面的例子一定可以知道我们只需要使用 `Types::user('mutation')` 即可。

接下来我们在配置中将这个类型加入到 `schema` 中，就可以通过不同的GraphQL指令对应到不同的入口类型了。

```php
<?php

return [
    'types' => [
        'graph' => [
            'query' => \app\http\graph\QueryType::class
            'mutation' => \app\http\graph\MutationType::class
        ]
    ],
    'schema' => [
        'graph'
    ],
    'routePrefix' => 'api/'
];
```

接下来我们使用GraphQL请求我们的接口 `/api/graph` ，可以看出我们将我们定义的类型名加入到 `schema` 中，这个类型就可以作为一个入口了。

使用 `query` 指令请求时会使得 `\app\http\graph\QueryType::class` 作为入口。
```
query {
  user {
    id
  }
}
```

而使用 `mutation` 指令请求时则会使得 `\app\http\graph\MutationType::class` 作为入口。

```
mutation {
  user {
    id
  }
}
```
