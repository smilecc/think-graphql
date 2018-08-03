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
