# 内置类型

内置类型或称基础类型，这些类型基本上是语言构成的基础类型。

## 可用类型

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

## 注意

- `int` 类型在32位php中会是 `int32` 类型，所以没有办法存储例如手机号等较长数字，可以使用 `float` 代替
- `id` 类型在json输出中认为是字符串类型，这是为了针对于例如 `uuid` 等字符类型的id
