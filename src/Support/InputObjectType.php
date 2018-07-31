<?php
namespace smilecc\think\Support;

use GraphQL\Type\Definition\InputObjectType as GraphQLInputObjectType;

class InputObjectType extends GraphQLInputObjectType
{
    public function __construct($config = null)
    {
        // 获取属性
        $attrs = $this->attrs();
        $self = $this;

        if (empty($config)) {
            $config = [
                'name' => $attrs['name'],
                'desc' => $attrs['desc'],
                'fields' => function () use ($self) {
                    return $self->fields();
                }
            ];
        }

        // 替换简称
        $config['description'] = $config['desc'];
        unset($config['desc']);

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
}
