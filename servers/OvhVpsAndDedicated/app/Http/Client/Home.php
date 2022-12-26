<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\Http\Client;


use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Api\OvhApiFactory;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Traits\WhmcsParamsApp;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Home\Pages\ControlPanel;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Product\Pages\NoActiveMachine;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Product\Pages\ServerInformation;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Client\Home\Pages\Vps\ServiceManagement;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Client\Home\Pages\Dedicated\ServiceManagement as DedicatedServiceManagement;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\Helper;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Client\Home\Pages\Dedicated\ControlPanel as DedicatedControlPanel;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Client\Home\Pages\Dedicated\ServerInformation as ServerInformationDedicated;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\Account\Utilities\ServerStrategyProvider;


/**
 * Description of Clientsservices
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class Home extends ClientBaseController
{
    use WhmcsParamsApp;

    public function index()
    {
        $serverStrategyProvider = new ServerStrategyProvider();
        $server = $serverStrategyProvider->getServerStrategy($this->getWhmcsEssentialParams());
        if(!is_object($server))
        {
            return Helper\view();
        }
        try
        {
            $server->getInfo();
        }
        catch (\Exception $exception)
        {
            return Helper\view()
                ->addElement(NoActiveMachine::class);
        }

        $serverType = $this->getServerType();

        if (!method_exists($this, $serverType))
        {
            return Helper\view();
        }

        try
        {
            return $this->{$serverType}();
        }
        catch (\Exception $exception)
        {
            \logModuleCall('OvhVpsAndDedicated', __CLASS__, var_export($exception, true), []);
        }
    }

    private function vps()
    {
        return Helper\view()
            ->addElement(ControlPanel::class)
            ->addElement(ServiceManagement::class)
            ->addElement(ServerInformation::class);
    }

    private function dedicated()
    {
        return Helper\view()
            ->addElement(DedicatedControlPanel::class)
            ->addElement(DedicatedServiceManagement::class)
            ->addElement(ServerInformationDedicated::class);
    }

}
