<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\Traits;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\ServiceManager;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Traits\WhmcsParams;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\WhmcsParams as WhmcsParamsHelper;

trait WhmcsParamsApp
{
    use WhmcsParams;

    private $whmcsAppParams;

    public function getAppParams($keys)
    {
        $params = $this->getWhmcsParamsByKeys($keys);

        $values = array_count_values($params);

        if(!$values || empty($values))
        {
            $params = $this->getAppWhmcsParams($keys);
        }

        return $params;
    }

    private function getParamByKey($key, $default = null)
    {
        $this->setAppParams();

        if(isset($this->whmcsAppParams[$key]))
        {
            return $this->whmcsAppParams[$key];
        }

        return $default;
    }

    private function getAppWhmcsParams(array $keys, $default = null)
    {
        $selectedParams = [];
        foreach ($keys as $key)
        {
            $selectedParams[$key] = $this->getParamByKey($key, $default);
        }

        return $selectedParams;
    }

    private function setAppParams()
    {
        $serviceManager = new ServiceManager();
        $this->whmcsAppParams = $serviceManager->getParams();
    }

    public function getWhmcsAppParams()
    {
         if(!$this->whmcsAppParams)
         {
             $this->setAppParams();
         }

         return $this->whmcsAppParams;
    }

    public function getWhmcsEssentialParams()
    {
        return $this->getAppWhmcsParams(WhmcsParamsHelper::getEssentialsKeys());
    }
}