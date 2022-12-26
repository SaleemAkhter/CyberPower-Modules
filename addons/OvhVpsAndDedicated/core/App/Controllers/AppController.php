<?php

namespace ModulesGarden\OvhVpsAndDedicated\Core\App\Controllers;

use ModulesGarden\OvhVpsAndDedicated\Core\ServiceLocator;

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
