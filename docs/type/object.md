# 对象类型（ObjectType）

## 使用方式

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

## 可重写的方法

方法名 | 返回类型 | 说明
--- | --- | ---
attrs | array | 定义
int | 整型
float | 浮点数
string | 字符串
boolean | 布尔值
nonNull | 非空类型
listOf | 列表类型

### attrs(): array

定义当前类型的属性

```php
public function attrs()
{
    return [
        'name' => 'ExampleType',
        'desc' => '演示类型'
    ];
}
```

字段名 | 说明
--- | ---
name | **必需的** 当前类型的名称
desc | 当前类型的描述

### fields(): array

定义当前类型的返回字段

```php
public function fields()
{
    return [
        'simple' => Types::string(),
        'custom' => [
            'name' => 'CustomField',
            'type' => Types::string(),
            'desc' => '自定义字段',
            'args' => [
                'id' => Types::id(),
                'example' => [
                    'name' => 'ExampleArgField',
                    'type' => Types::string(),
                    'desc' => '实例参数字段',
                    'defaultValue' => '默认值'
                ]
            ]
        ]
    ];
}
```

以下是当字段类型为一个数组时，可选的数组成员：

字段名 | 说明
--- | ---
type | **必需的** 字段的类型
name | 字段的名称
desc | 描述
args | 参数列表
deprecationReason | 标识这个字段已经废弃的原因，当不为空时，GraphQL的内省方法不再返回该字段，但是仍然能使用该字段查询

#### args字段

字段名 | 说明
--- | ---
type | **必需的** 参数的类型
name | 参数的名称
desc | 描述
defaultValue | 默认值

### fieldsMap(): array

通过重写这个方法，你可以改变 `fields` 中某个字段在数据源中的指向。

例如 `fields` 中存在一个叫 `title` 的字段，但是它在数据源中的实际字段名叫 `article_title`，那么我们只需要使用如下代码就可以将其指向到它的实际数据源。

```php
public function fieldsMap()
{
    return [
        'title' => 'article_title'
    ];
}
```

### resolveField($value, $args, $context, $info): any

**注意** 当您重写了这个方法的时候，think-graphql的自动调用 `resolve字段名` 和自动从 `$value` 中返回数据的功能将**失效**，而将使用您定义的 `resolveField` 方法的返回值。

所以您可以重写这个函数来自定义字段 `resolve` 机制，以下这个例子是在无 `resolve字段名` 方法时返回默认值 `[]` 。
```php
public function resolveField($val, $args, $context, \GraphQL\Type\Definition\ResolveInfo $info)
{
    $methodName = "resolve" . str_replace('_', '', $info->fieldName);
    if (method_exists($this, $methodName)) {
        return $this->{$methodName}($val, $args, $context, $info);
    } else {
        return [];
    }
}
```
