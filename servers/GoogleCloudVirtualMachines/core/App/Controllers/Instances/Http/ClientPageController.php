<?php

namespace ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\App\Controllers\Instances\Http;

use \ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\App\Controllers\Interfaces\ClientArea;
use \ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\App\Controllers\Instances\HttpController;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\Hook\InternalHooksWrapper;

class ClientPageController extends HttpController implements ClientArea
{
    public function execute($params = null)
    {
        //run hook before client area page is loaded
        $hookWrapper = new InternalHooksWrapper();
        $newParams = $hookWrapper->runInternalHook('PreClientAreaPageLoad', $params);
        if ($newParams && is_array($newParams))
        {
            $params = $newParams;
        }

        return parent::execute($params);
    }
}
