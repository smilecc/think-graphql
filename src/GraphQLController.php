<?php
namespace smilecc\think;

use think\Controller;
use smilecc\think\GraphQLMiddleware;
use smilecc\think\Support\Types;
use \GraphQL\Type\Schema;
use \GraphQL\GraphQL;

class GraphQLController extends Controller
{
    public function action($action)
    {
        // 获取schema
        $config = config('graph.');
        $types = $config['types'];
        $schemaTypes = [];

        if (in_array($action, $config['schema']) || array_key_exists($action, $config['schema'])) {
            // 如果为key则使用其value作为action
            if (array_key_exists($action, $config['schema'])) {
                $action = $config['schema'][$action];
            }
        } else {
            throw new \think\exception\HttpException(404, "[$action] 未在 schema 中定义");
        }

        // 判断action是否在types中
        if (!array_key_exists($action, $types)) {
            throw new \think\exception\HttpException(404, "Type [$action] 未在 types 中定义");
        }
        
        // 构建当前action对应的获取schema
        if (gettype($types[$action]) == 'array') {
            foreach ($types[$action] as $key => $typeClass) {
                $schemaTypes[$key] = Types::{$action}($key);
            }
        } else {
            $schemaTypes['query'] = Types::{$action}('query');
        }

        $schema = new Schema($schemaTypes);

        // 从请求中获取数据
        $input = json_decode(file_get_contents('php://input'), true);
        $query = $input['query'];
        $variables = !empty($input) && array_key_exists('variables', $input) ? $input['variables'] : null;
        $rootValue = [];

        if (empty($query)) {
            return json()->data([
                'error' => 'query is empty!'
            ]);
        }

        $output = GraphQL::executeQuery($schema, $query, $rootValue, [], $variables)->toArray(input('?debug'));
        return json()->data($output);
    }
}
