<?php
namespace __NAMESPACE;

use \smilecc\think\Support\Types;
use \smilecc\think\Support\ObjectType;

class __CLASS_NAME extends ObjectType
{
    /**
     * 请记得在配置中的types数组添加以下配置
     * '__TYPE_NAME' => __NAMESPACE\__CLASS_NAME::class
     */


    /**
     * 类型描述
     *
     * @return array
     */
    public function attrs()
    {
        return [
            'name' => '__CLASS_NAME',
            'desc' => ''
        ];
    }

    /**
     * 类型所包含的字段
     *
     * @return array
     */
    public function fields()
    {
        return [
            'example' => [
                'type' => Types::string(),
                'args' => []
            ]
        ];
    }

    /**
     * 演示字段的处理函数
     *
     * @param [type] $value 上层Type中传递的数据
     * @param [type] $args 本次请求提供的参数
     * @return any
     */
    public function resolveExample($value, $args, $context, $info)
    {
        return 'This is example field';
    }
}