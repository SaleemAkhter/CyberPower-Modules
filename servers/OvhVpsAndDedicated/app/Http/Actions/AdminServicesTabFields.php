<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\Http\Actions;


use ModulesGarden\Servers\OvhVpsAndDedicated\Core\App\Controllers\Instances\AddonController;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\Account\Utilities\ServerStrategyProvider;


class AdminServicesTabFields extends AddonController
{
    public function execute($params = null)
    {
        $serverStrategyProvider = new ServerStrategyProvider();
        $server = $serverStrategyProvider->getServerStrategy($params);

        try
        {
            if(!is_object($server))
            {
                return;

            }
            $server->getInfo();
        }
        catch (\Exception $exception)
        {
            return;
        }

        return [\ModulesGarden\Servers\OvhVpsAndDedicated\App\Http\Admin\Server\ProductPage::class, 'servicePageIndex'];
    }
}
