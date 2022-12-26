<?php

namespace ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\App\Controllers\AppControllers;

use ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\App\Controllers\Instances\AddonController;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\App\Controllers\Interfaces\AppController;

class Addon extends \ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\App\Controllers\AppController implements AppController
{
    public function getControllerInstanceClass ($callerName, $params)
    {
        $functionName = str_replace($this->getModuleName() . '_', '', $callerName);

        $coreAddon = '\ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\App\Controllers\Instances\Addon\\' . ucfirst($functionName);
        if (class_exists($coreAddon) && is_subclass_of($coreAddon, AddonController::class))
        {
            return $coreAddon;
        }

        $appAddon = '\ModulesGarden\Servers\GoogleCloudVirtualMachines\App\Http\Actions\\' . ucfirst($functionName);
        if (class_exists($appAddon) && is_subclass_of($appAddon, AddonController::class))
        {
            return $appAddon;
        }

        return null;
    }

    public function getModuleName ()
    {
        return 'GoogleCloudVirtualMachines';
    }
}
