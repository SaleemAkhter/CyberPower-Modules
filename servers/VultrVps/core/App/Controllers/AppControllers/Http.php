<?php

namespace ModulesGarden\Servers\VultrVps\Core\App\Controllers\AppControllers;

use ModulesGarden\Servers\VultrVps\Core\App\Controllers\Instances\Http\AdminPageController;
use ModulesGarden\Servers\VultrVps\Core\App\Controllers\Instances\Http\ClientPageController;
use ModulesGarden\Servers\VultrVps\Core\App\Controllers\Interfaces\AppController;

class Http extends \ModulesGarden\Servers\VultrVps\Core\App\Controllers\AppController implements AppController
{
    public function getControllerInstanceClass ($callerName, $params)
    {
        //todo
        $functionName = str_replace($this->getModuleName() . '_', '', $callerName);
        switch ($functionName)
        {
            //HTTP controllers
            case 'output':
                return AdminPageController::class;
            case 'clientarea':
                return ClientPageController::class;
        }

        return null;
    }

    public function getModuleName ()
    {
        return 'VultrVps';
    }
}
