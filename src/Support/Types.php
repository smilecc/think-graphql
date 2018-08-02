<?php
namespace smilecc\think\Support;

use \GraphQL\Type\Definition\Type;
use \GraphQL\Type\Definition\NonNull;
use \GraphQL\Type\Definition\ListOfType;

class Types
{
    // GraphQL的配置
    protected static $config;
    // 存储已经生成的type
    private static $typeList = [];

    public static function __callStatic($name, $arguments)
    {
        self::$config = config('graph.');

        if (count($arguments) > 0) {
            $typeConfig = [];
            $typeName = $name;

            // 判断是否有参数
            if (array_key_exists(1, $arguments)) {
                $typeConfig = $arguments[1];
                // 如果设置了TypeName 则改变TypeName
                if (array_key_exists('name', $typeConfig)) {
                    $typeName = $typeConfig['name'];
                }
            }

            return self::getType($name, $typeName, $arguments[0], $typeConfig);
        } else {
            return self::getType($name, $name, 'query', []);
        }
    }

    /**
     * 获取Type
     *
     * @param [type] $className
     * @param [type] $typeName
     * @param [type] $directive
     * @param [type] $arguments
     * @return void
     */
    private static function getType($className, $typeName, $directive, $arguments)
    {
        $typeListKey = "{$typeName}_{$directive}";
        // 如果已经创建该Type则直接从TypeList中返回
        if (array_key_exists($typeListKey, self::$typeList)) {
            return self::$typeList[$typeListKey];
        }

        // 创建Type
        if (!array_key_exists($className, self::$config['types'])) {
            throw new \Exception("Type '{$typeName}' 不存在", 1);
        }

        // 获取当前Class对应的Type列表
        $typeClassList = self::$config['types'][$className];

        // 如果不为array的话视为query
        if (gettype($typeClassList) != 'array') {
            // 如果当前指令为query 则直接使用该Class
            if ($directive != 'query') {
                throw new \Exception("Type '{$typeName}' 中不存在指令 {$directive}", 1);
            }
            return self::$typeList[$typeListKey] = new $typeClassList($arguments);
        }

        // 从 Type 通过 directive（指令） 找到对应的 TypeClass
        if (!array_key_exists($directive, $typeClassList)) {
            throw new \Exception("Type '{$typeName}' 中不存在指令 {$directive}", 1);
        }
        return self::$typeList[$typeListKey] = new $typeClassList[$directive]($arguments);
    }

    public static function boolean()
    {
        return Type::boolean();
    }

    public static function float()
    {
        return Type::float();
    }

    public static function id()
    {
        return Type::id();
    }

    public static function int()
    {
        return Type::int();
    }

    public static function string()
    {
        return Type::string();
    }

    public static function nonNull($type)
    {
        return new NonNull($type);
    }

    public static function listOf($type)
    {
        return new ListOfType($type);
    }
}
