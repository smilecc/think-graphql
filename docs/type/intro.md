# 介绍

在GraphQL中，类型（Type）起到非常重要的作用。

在`think-graphql`中，所有的类型父类都定义在 `smilecc\think\Support` 中，类 `smilecc\think\Support\Types` 是所有Type的注册表。

## 定义类型

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
        ];
    }

    public function fields()
    {
        return [
            'id' => Types::id()
        ];
    }
}
```

## 类型注册表

类 `smilecc\think\Support\Types` 是所有Type的注册表，通过调用该类的静态方法可获取到所有的**内置类型**和**自定义类型**。

```php
<?php

use smilecc\think\Support\Types;

Types::int();
Types::string();
```

