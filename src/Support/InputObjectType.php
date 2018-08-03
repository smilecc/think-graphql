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
                    return $self->processFields($self->fields());
                }
            ];
        }

        // 替换简称
        if (array_key_exists('desc', $config)) {
            $config['description'] = $config['desc'];
            unset($config['desc']);
        }
        if (array_key_exists('fields', $config)) {
            $config['fields'] = $this->processFields($config['fields']);
        }

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

    /**
     * 处理fields
     * 处理简写
     *
     * @param [type] $fields
     * @return void
     */
    private function processFields($fields)
    {
        if (is_array($fields)) {
            foreach ($fields as $key => &$field) {
                if (is_array($field) && array_key_exists('desc', $field)) {
                    $field['description'] = $field['desc'];
                }
            }
        }
        return $fields;
    }
}
