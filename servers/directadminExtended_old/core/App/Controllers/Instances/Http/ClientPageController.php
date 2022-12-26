<?php

namespace ModulesGarden\Servers\DirectAdminExtended\Core\App\Controllers\Instances\Http;

use \ModulesGarden\Servers\DirectAdminExtended\Core\App\Controllers\Interfaces\ClientArea;
use \ModulesGarden\Servers\DirectAdminExtended\Core\App\Controllers\Instances\HttpController;

class ClientPageController extends HttpController implements ClientArea
{
    public function execute($params = null)
    {
        if (class_exists('\ModulesGarden\Servers\DirectAdminExtended\App\Hooks\InternalHooks\PreClientAreaPageLoad'))
        {
            $preClietAreaHook = new \ModulesGarden\Servers\DirectAdminExtended\App\Hooks\InternalHooks\PreClientAreaPageLoad($params);
            $newParams = $preClietAreaHook->execute();
            if ($newParams && is_array($newParams))
            {
                $params = $newParams;
            }
        }
        
        return parent::execute($params);
    }
}
