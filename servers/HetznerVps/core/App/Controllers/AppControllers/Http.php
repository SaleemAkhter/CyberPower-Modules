<?php

namespace ModulesGarden\Servers\HetznerVps\Core\App\Controllers\AppControllers;

use ModulesGarden\Servers\HetznerVps\Core\App\Controllers\Interfaces\AppController;
use ModulesGarden\Servers\HetznerVps\Core\App\Controllers\Instances\Http\AdminPageController;
use ModulesGarden\Servers\HetznerVps\Core\App\Controllers\Instances\Http\ClientPageController;
use ModulesGarden\Servers\HetznerVps\Core\Traits\AppParams;

class Http extends \ModulesGarden\Servers\HetznerVps\Core\App\Controllers\AppController implements AppController
{
    use AppParams;

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
        return $this->getAppParam('systemName');
    }
}
