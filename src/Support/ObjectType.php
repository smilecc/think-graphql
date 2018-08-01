<?php
namespace smilecc\think\Support;

use \GraphQL\Type\Definition\ResolveInfo;
use \GraphQL\Type\Definition\ObjectType as GraphQLObjectType;

class ObjectType extends GraphQLObjectType
{
    public $typeConfig;

    public function __construct($args)
    {
        $this->typeConfig = $args;
        // 获取属性
        $attrs = $this->getAttrs($args);
        $self = $this;

        $config = [
            'name' => $attrs['name'],
            'description' => $attrs['desc'],
            'fields' => function () use ($self, $args) {
                // 判断是否从args传入
                if (array_key_exists('fields', $args)) {
                    return array_merge($self->fields(), $args['fields']);
                }

                return $self->fields();
            },
            'resolveField' => function($val, $args, $context, ResolveInfo $info) {
                // 替换fieldName中的_下划线
                $methodName = "resolve" . str_replace('_', '', $info->fieldName);

                if (method_exists($this, $methodName)) {
                    return $this->{$methodName}($val, $args, $context, $info);
                } else {
                    return array_key_exists($info->fieldName, $val) ? $val[$info->fieldName] : null;
                }
            }
        ];

        parent::__construct($config);
    }

    public function attrs()
    {
        return [
            'name' => '',
            'desc' => ''
        ];
    }

    public function getAttrs($args)
    {
        $default = [
            'name' => '',
            'desc' => ''
        ];

        return array_merge($default, $this->attrs(), $args);
    }

    public function fields()
    {
        return [];
    }
}
