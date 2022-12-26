<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Product\Providers;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\Ovh\ServiceInformation\ServiceInformation;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\WhmcsParams;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Traits\WhmcsParamsApp;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Widget\Forms\DataProviders\BaseDataProvider;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Facade\Repository\Vps\Repository;
use function ModulesGarden\Servers\OvhVpsAndDedicated\Core\Helper\sl;

/**
 * Description of Rebuild
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class ServerMonitoringProvider extends BaseDataProvider implements ClientArea, AdminArea
{
    use WhmcsParamsApp;

    public function read()
    {

    }

    public function create()
    {
    }

    public function delete()
    {
    }

    public function update()
    {

    }

    public function getMonitoringData()
    {
        try {
            $vps = (new Repository($this->getAppWhmcsParams(WhmcsParams::getEssentialsKeys())))->get();
            $data = ServiceInformation::convertStatuses($vps->status());
        }catch (\Exception $ex){
            if($ex->getMessage()=="not implemented"){
                throw new \Exception(sl("lang")->abtr('Service Monitoring request failed'));
            }
        }
        return $data;
    }


}
