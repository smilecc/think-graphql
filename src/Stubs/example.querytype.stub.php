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
            'user' => [
                'type' => Types::user(),
                'args' => [
                    'id' => Types::nonNull(Types::id())
                ]
            ]
        ];
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
