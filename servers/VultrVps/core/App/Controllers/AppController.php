<?php

namespace ModulesGarden\Servers\VultrVps\Core\App\Controllers;

use ModulesGarden\Servers\VultrVps\Core\ServiceLocator;

abstract class AppController
{
    public function runController($callerName, $params)
    {
        $controller = $this->getControllerInstanceClass($callerName, $params);

        $controllerInstance = ServiceLocator::call($controller);

        $result = $controllerInstance->runExecuteProcess($params);

        return $result;
    }
}
