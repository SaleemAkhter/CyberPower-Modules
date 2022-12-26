<?php

namespace ModulesGarden\Servers\HetznerVps\Core\App\Controllers;

use ModulesGarden\Servers\HetznerVps\Core\ServiceLocator;

abstract class AppController
{
    public function runController($callerName, $params)
    {
        $controller = $this->getControllerInstanceClass($callerName, $params);

        $controllerInstance = ServiceLocator::call($controller);

        return $controllerInstance->runExecuteProcess($params);
    }
}
