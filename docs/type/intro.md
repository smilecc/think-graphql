# 介绍

在GraphQL中，类型（Type）起到非常重要的作用。

在`think-graphql`中，所有的类型都定义在 `smilecc\think\Support` 中，类 `smilecc\think\Support\Types` 是所有Type的注册表。

## 类型注册表

类 `smilecc\think\Support\Types` 是所有Type的注册表，通过调用该类的静态方法可获取到所有的**内置类型**和**自定义类型**。

```php
<?php

use smilecc\think\Support\Types;

Types::int();
Types::string();

```

## 内置类型

GraphQL定义了一些内置类型，这些类型基本上是语言的基础类型，所有内置类型如下：

类型 | 说明
--- | ---
id | id类型，可视为字符串
int | 整型
float | 浮点数
string | 字符串
boolean | 布尔值
nonNull | 非空类型
listOf | 列表类型

其中 `nonNull` 和 `listOf` 类型是用来定义子类型特性的，例如：
```php
<?php
use smilecc\think\Support\Types;

// 定义一个不允许为null的string类型
Types::nonNull(Types::string());

// 定义一个int的数组类型
Types::listOf(Types::int());
```

## 自定义类型

自定义类型可以理解为一种复合类型。

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
