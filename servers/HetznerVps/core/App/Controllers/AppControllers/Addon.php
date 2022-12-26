<?php

namespace ModulesGarden\Servers\HetznerVps\Core\App\Controllers\AppControllers;

use ModulesGarden\Servers\HetznerVps\Core\App\Controllers\Instances\AddonController;
use ModulesGarden\Servers\HetznerVps\Core\App\Controllers\Interfaces\AppController;
use ModulesGarden\Servers\HetznerVps\Core\Traits\AppParams;

class Addon extends \ModulesGarden\Servers\HetznerVps\Core\App\Controllers\AppController implements AppController
{
    use AppParams;

    public function getControllerInstanceClass ($callerName, $params)
    {
        $functionName = str_replace($this->getModuleName() . '_', '', $callerName);

        $coreAddon = '\ModulesGarden\Servers\HetznerVps\Core\App\Controllers\Instances\Addon\\' . ucfirst($functionName);
        if (class_exists($coreAddon) && is_subclass_of($coreAddon, AddonController::class))
        {
            return $coreAddon;
        }

        $appAddon = '\ModulesGarden\Servers\HetznerVps\App\Http\Actions\\' . ucfirst($functionName);
        if (class_exists($appAddon) && is_subclass_of($appAddon, AddonController::class))
        {
            return $appAddon;
        }

        return null;
    }

    public function getModuleName ()
    {
        return $this->getAppParam('systemName');
    }
}
