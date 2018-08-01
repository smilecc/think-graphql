<?php
namespace smilecc\think;

use think\console\Command;
use think\console\Input;
use think\console\input\Argument;
use think\console\input\Option;
use think\console\Output;
use think\facade\Env;

class GraphQLCommand extends Command
{
    protected function configure()
    {
        $this->setName('graph')
            ->addArgument('action', Argument::REQUIRED, "Graph actions [init]")
            ->addOption('force', null, null, '')
            ->addOption('type', null, Option::VALUE_REQUIRED, '')
        	->setDescription('Think GraphQL helper');
    }

    protected function execute(Input $input, Output $output)
    {
        $name = trim($input->getArgument('action'));
        if ($name == 'init') {
            $this->initAction($input, $output);
        } else if ($name == 'make') {
            $this->makeAction($input, $output);
        }
    }

    protected function initAction(Input $input, Output $output)
    {
        $configPath = Env::get('config_path') . '/graph.php';
        $routePath = Env::get('route_path') . '/graph.php';

        if ((file_exists($configPath) || file_exists($routePath)) && !$input->hasOption('force')) {
            throw new \Exception("The configuration file already exists. If you need to regenerate, please use --force.\ne.g. php think graph init --force", 1);
        }


        $this->saveStub('config', $configPath);
        $this->saveStub('route', $routePath);

        $this->saveStub('example.querytype', Env::get('app_path') . '/http/graph/QueryType.php');
        $this->saveStub('example.usertype', Env::get('app_path') . '/http/graph/User/UserType.php');

        $output->writeln("Initialization completed!");
    }

    protected function makeAction(Input $input, Output $output)
    {
        if (!$input->hasOption('type')) {
            throw new Exception("Error Processing Request", 1);
            return;
        }
        $name = $input->getOption('type');
        
        $className = $this->getClassName($name);
        $namespace = $this->getNamespace($className);

        $path = Env::get('app_path') . $name . '.php';

        if (file_exists($path) && !$input->hasOption('force')) {
            throw new \Exception("The type file already exists. If you need to regenerate, please use --force.\ne.g. php think graph make --type ${name} --force", 1);
        }

        $this->saveStub('type', $path, function ($stub) use ($className, $namespace) {
            // 获取class
            $class = array_slice(explode('\\', $className), -1, 1)[0];
            // 获取TypeName
            $type = strtolower(substr($class, 0, 1)) . substr($class, 1);
            $type = preg_replace('/Type$/', '', $type);
            // 替换占位符
            return str_replace(['__CLASS_NAME', '__NAMESPACE', '__TYPE_NAME'], [
                $class,
                $namespace,
                $type
            ], $stub);
        });

        $output->writeln("Create completed!");
    }

    protected function saveStub($stubName, $to, $processer = null)
    {
        if (!is_dir(dirname($to))) {
            mkdir(dirname($to), 0755, true);
        }

        $stub = file_get_contents($this->getStubPath($stubName));
        if ($processer) {
            $stub = $processer($stub);
        }
        file_put_contents($to, $stub);
    }

    protected function getStubPath($filename)
    {
        return __DIR__ . DIRECTORY_SEPARATOR . 'Stubs' . DIRECTORY_SEPARATOR . $filename . '.stub.php';
    }

    protected function getClassName($name)
    {
        return str_replace('/', '\\', $name);
    }

    protected function getNamespace($name)
    {
        return env('app_namespace') . '\\' . trim(implode('\\', array_slice(explode('\\', $name), 0, -1)), '\\');
    }
}
