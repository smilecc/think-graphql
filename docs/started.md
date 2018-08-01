# 开始

## 说明

1. 使用GraphQL需要您事先了解GraphQL的运作机制，请先阅读[GraphQL官方文档](http://graphql.cn/)。
2. 本项目是在`graphql-php`的基础上二次封装，所有的Type都完全兼容`graphql-php`。
3. 在使用前建议您阅读文档、查看Demo（正在开发）来了解使用方法。

## 安装

```bash
$ composer require smilecc/think-graphql:dev-master
```
注意：由于 ThinkPHP 5.1 对比 5.0 有较大改变，所以目前只支持新版 5.1。

## 使用

首先需要在`/application/command.php`中增加一个指令。

```php
<?php

return [
    'smilecc\think\GraphQLCommand'
];
```

然后在项目根目录下使用如下命令初始化框架

```bash
$ php think graph init
```

运行该命令之后如果提示初始化成功，则可以在`/config/graph.php`看到生成出的配置文件，以及在`/application/http/graph`文件夹下生成出的实例项目。

在初始化完毕之后，你可以使用GraphQL的测试工具请求`http://localhost/api/graph`进行尝试，正确安装的情况下会有如下的响应。

Query内容：
```
{
  user(id: 1){
    id
    nickname
    created_time
  }
}
```

响应内容：
```json
{
  "data": {
    "user": {
      "id": "1",
      "nickname": "TestUser",
      "created_time": "1533028910"
    }
  }
}
```