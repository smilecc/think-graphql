# 变更与输入类型

GraphQL 的大部分讨论集中在数据获取，但是任何完整的数据平台也都需要一个改变服务端数据的方法。

## 变更（Mutation）

如果您还不了解`Mutation`建议您先阅读[Mutation的官方文档](http://graphql.cn/learn/queries/#mutations)。

我们可以理解为，Mutation这个字段只是带有参数请求的常规类型，普通的 `query` 请求和带有参数的变更（Mutation）并没有实际上的差别，这只是一种语法上的定义。

以下是一个变更操作：

```
mutation UserRegister($tel: String, $pwd: String) {
  createUser(telephone: $tel, password: $pwd) {
    id
    token
  }
}
```

为了执行以上请求，你可以定义如下类型来响应这个请求。

```php
<?php
namespace app\http\graph;

use \smilecc\think\Support\Types;
use \smilecc\think\Support\ObjectType;

class QueryMutationType extends ObjectType
{
    public function attrs()
    {
        return [
            'name' => 'GraphQueryType'
        ];
    }

    public function fields()
    {
        return [
            'createUser' => [
                'type' => new ObjectType([
                    'name' => 'UserType',
                    'desc' => '测试用户类型',
                    'fields' => [
                        'id' => Types::id(),
                        'token' => Types::string()
                    ]
                ]),
                'args' => [
                    'telephone' => Types::string(),
                    'password' => Types::string()
                ]
            ]
        ];
    }

    public function resolveCreateUser($val, $args)
    {
        return [
            'id' => 1001,
            'token' => 'test'
        ];
    }
}
```


## 输入类型（InputObjectType）

GraphQL中为可以为复杂的输入定义输入类型，它的作用主要是当输入的数据非常多的时候，如果在参数中一个一个输入未免太过麻烦，我们可以将其定义成输入类型然后在 `variables` 中传入。

GraphQL对于输入类型的[官方文档在此](http://graphql.cn/learn/schema/#input-types)。

在 `think-graphql` 中，输入类型是 `smilecc\think\Support\InputObjectType` （或它子类）的实例，你可以直接传入一个数组构造它。

```php
<?php
use \smilecc\think\Support\Types;
use \smilecc\think\Support\InputObjectType;

$userInputType = new InputObjectType([
    'name' => 'UserInputType',
    'desc' => '用户的输入类型',
    'fields' => [
        'telephone' => Types::string(),
        'password' => Types::string()
    ]
])
```

可以看出，`InputObjectType` 类似于 `ObjectType`，只是没有 `args`。

### 使用输入类型

```php
<?php
use \smilecc\think\Support\Types;
use \smilecc\think\Support\ObjectType;
use \smilecc\think\Support\InputObjectType;

class QueryType extends ObjectType
{
    public function attrs()
    {
        return [
            'name' => 'GraphQueryType'
        ];
    }

    public function fields()
    {
        return [
            'createUser' => [
                'type' => Types::user(),
                'args' => [
                    'user' => new InputObjectType([
                        'name' => 'UserInputType',
                        'desc' => '用户的输入类型',
                        'fields' => [
                            'telephone' => Types::string(),
                            'password' => Types::string()
                        ]
                    ])
                ]
            ]
        ];
    }

    public function resolveCreateUser($val, $args)
    {
        // 我们输入的数据
        $user = $args['user'];
    }
}
```

随后我们就可以在 `mutation` 时通过 `variables` 传入用户数据了。

请求内容：
```
mutation createUser($user: UserInputType) {
  createUser(user: $user) {
    id
    token
  }
}
```

variables 内容：
```
{
  "user": {
    "telephone": "123",
    "password": "abc"
  }
}
```
