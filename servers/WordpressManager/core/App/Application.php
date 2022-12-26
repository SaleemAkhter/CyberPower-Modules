<?php

namespace ModulesGarden\WordpressManager\Core\App;

use \ModulesGarden\WordpressManager\Core\App\Controllers\AppControllers\Http;
use \ModulesGarden\WordpressManager\Core\App\Controllers\AppControllers\Addon;
use \ModulesGarden\WordpressManager\Core\App\Controllers\AppControllers\Api;
use \ModulesGarden\WordpressManager\Core\ServiceLocator;

class Application
{
    public function run($callerName = null, $params = null)
    {
        try
        {
            $controller = $this->getControllerClass($callerName);

            $controllerInstance = ServiceLocator::call($controller);

            $result = $controllerInstance->runController($callerName, $params);

            return $result;
        }
        catch (\Exception $exc)
        {
            $errorPage = ServiceLocator::call(Controllers\Instances\Http\ErrorPage::class);

            $params['mgErrorDetails'] = $exc;

            $result = $errorPage->execute($params);
            
            return $result;
        }
    }

    public function getControllerClass($callerName = null)
    {
        $functionName = str_replace($this->getModuleName() . '_', '', $callerName);
        switch ($functionName)
        {
            //HTTP controllers
            case 'output':
                return Http::class;
            case 'clientarea':
                return Http::class;

            //API controller
            case 'api':
                return Api::class;

            //Addon controllers
            default:
                return Addon::class;
        }
    }

    public function getModuleName ()
    {
        return 'WordpressManager';
    }
}
