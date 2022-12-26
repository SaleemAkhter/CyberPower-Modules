<?php

namespace ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\App\Controllers\AppControllers;

use ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\App\Controllers\Interfaces\AppController;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\App\Controllers\Instances\Http\AdminPageController;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\App\Controllers\Instances\Http\ClientPageController;

class Http extends \ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\App\Controllers\AppController implements AppController
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
        return 'GoogleCloudVirtualMachines';
    }
}
