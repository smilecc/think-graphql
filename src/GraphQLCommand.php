<?php
namespace smilecc\think;

use think\console\Command;
use think\console\Input;
use think\console\input\Argument;
use think\console\Output;
use think\facade\Env;

class GraphQLCommand extends Command
{
    protected function configure()
    {
        $this->setName('graph')
            ->addArgument('action', Argument::REQUIRED, "Graph actions [init]")
            ->addOption('force', null, null, '')
        	->setDescription('Think GraphQL helper');
    }

    protected function execute(Input $input, Output $output)
    {
        $name = trim($input->getArgument('action'));
        if ($name == 'init') {
            $this->initAction($input, $output);
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

    protected function saveStub($stubName, $to)
    {
        if (!is_dir(dirname($to))) {
            mkdir(dirname($to), 0755, true);
        }

        $stub = file_get_contents($this->getStubPath($stubName));
        file_put_contents($to, $stub);
    }

    protected function getStubPath($filename)
    {
        return __DIR__ . DIRECTORY_SEPARATOR . 'Stubs' . DIRECTORY_SEPARATOR . $filename . '.stub.php';
    }
}
