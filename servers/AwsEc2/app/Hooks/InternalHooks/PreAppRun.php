<?php


namespace ModulesGarden\Servers\AwsEc2\App\Hooks\InternalHooks;

use ModulesGarden\Servers\AwsEc2\Core\Hook\Interfaces\InternalHook;
use ModulesGarden\Servers\AwsEc2\Core\ModuleConstants;

class PreAppRun implements InternalHook
{
    private $params = false;

    public function __construct($params)
    {
        $this->params = $params;
    }

    public function execute()
    {
        if (!class_exists('\Aws\AwsClient'))
        {
            $path = ModuleConstants::getModuleRootDir() . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'Libs'
                . DIRECTORY_SEPARATOR . 'AwsLib' . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';
            if (file_exists($path))
            {
                require_once $path;
            }
        }
    }
}
