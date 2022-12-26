<?php

namespace ModulesGarden\WordpressManager\Core\App\Controllers;

use ModulesGarden\WordpressManager\Core\ServiceLocator;

abstract class AppController
{
    public function runController($callerName, $params)
    {
        $controller = $this->getControllerInstanceClass($callerName, $params);

        $controllerInstance = ServiceLocator::call($controller);

        $result = $controllerInstance->execute($params);

        return $result;
    }
}
