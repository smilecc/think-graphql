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
        
        // 构建当前action对应的获取schema
        foreach ($types[$action] as $key => $typeClass) {
            $schemaTypes[$key] = Types::{$action}($key);
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
