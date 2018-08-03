# 命令行

`think-graphql` 为你提供了一些常用、便捷的命令，使用命令行需要系统的环境变量中有 `php`。

以下所有命令的前缀都是 `php think graph`，且需要在站点的根目录中运行。

```bash
$ php think graph init
```

## 初始化

```bash
$ php think graph init
```

可选参数 | 说明
--- | ---
force | 强制重新初始化，**会覆盖掉之前的配置**，请小心使用，例如 `php think graph init --force`

## 创建

创建模版文件，目前支持生成`ObjectType`模版。

```bash
$ php think graph make --type http/BlogType
```

可选参数 | 说明
--- | ---
type | 生成一个`ObjectType`模版文件，生成出来的文件将会在`application`文件夹的对应路径中，例如 `php think graph make --type http/BlogType`将会生成`/application/http/BlogType.php`
force | 强制重新生成模版文件，例如 `php think graph make --type http/BlogTyp --force`

