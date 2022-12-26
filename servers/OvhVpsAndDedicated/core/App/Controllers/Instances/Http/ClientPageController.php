<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\Core\App\Controllers\Instances\Http;

use \ModulesGarden\Servers\OvhVpsAndDedicated\Core\App\Controllers\Interfaces\ClientArea;
use \ModulesGarden\Servers\OvhVpsAndDedicated\Core\App\Controllers\Instances\HttpController;

class ClientPageController extends HttpController implements ClientArea
{
    public function execute($params = null)
    {
        if (class_exists('\ModulesGarden\Servers\OvhVpsAndDedicated\App\Hooks\InternalHooks\PreClientAreaPageLoad'))
        {
            $preClietAreaHook = new \ModulesGarden\Servers\OvhVpsAndDedicated\App\Hooks\InternalHooks\PreClientAreaPageLoad($params);
            $newParams = $preClietAreaHook->execute();
            if ($newParams && is_array($newParams))
            {
                $params = $newParams;
            }
        }
        
        return parent::execute($params);
    }
}
