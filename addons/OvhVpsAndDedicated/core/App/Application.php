<?php

namespace ModulesGarden\OvhVpsAndDedicated\Core\App;

use \ModulesGarden\OvhVpsAndDedicated\Core\App\Controllers\AppControllers\Http;
use \ModulesGarden\OvhVpsAndDedicated\Core\App\Controllers\AppControllers\Addon;
use \ModulesGarden\OvhVpsAndDedicated\Core\App\Controllers\AppControllers\Api;
use \ModulesGarden\OvhVpsAndDedicated\Core\ServiceLocator;

use function \ModulesGarden\OvhVpsAndDedicated\Core\Helper\di;

class Application
{
    public function run($callerName = null, $params = null)
    {
        try
        {
            $this->setWhmcsParams($params);
            
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

    /** 
     * Saves provided WHMCS params to WhmcsParams service
     * @param type array
     */
    protected function setWhmcsParams($params)
    {
        $whmcsParams = di('whmcsParams');
        $whmcsParams->setParams($params);
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
        return 'OvhVpsAndDedicated';
    }
}
