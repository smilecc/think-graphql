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
        $attrs = $this->attrs();
        $self = $this;

        $config = [
            'name' => $attrs['name'],
            'description' => $attrs['desc'],
            'fields' => function () use ($self) {
                return $self->fields();
            },
            'args' => function () use ($self) {
                return $self->args();
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

    public function fields()
    {
        return [];
    }

    public function args()
    {
        return [];
    }
}
