<?php

namespace ModulesGarden\OvhVpsAndDedicated\App\UI\Admin\Servers\Providers;


use ModulesGarden\OvhVpsAndDedicated\Core\UI\Interfaces\AdminArea;
use ModulesGarden\OvhVpsAndDedicated\Core\UI\Traits\RequestObjectHandler;
use ModulesGarden\OvhVpsAndDedicated\Core\UI\Widget\Forms\DataProviders\BaseModelDataProvider;
use \ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Facade\Repository\Vps\Repository;

use ModulesGarden\OvhVpsAndDedicated\App\UI\Admin\Servers\Helpers\Machine as MachinePageHelper;
use ModulesGarden\OvhVpsAndDedicated\App\Libs\Repository\Manage\Machine;

/**
 * Class Vps
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class Vps extends BaseModelDataProvider implements AdminArea
{
    use RequestObjectHandler;

    public function __construct()
    {
        parent::__construct(null);
    }

    public function getVpsMachines($clientsGroupByMachineName)
    {
        $serverId = $this->getRequestValue('serverid');
        $params = [
            'serverid' => $serverId
        ];

        $vpsRepository = new Repository($params);

        $data = MachinePageHelper::assignExtraValues($vpsRepository->getAllToArray(), $clientsGroupByMachineName);

        return $data;
    }

    public function setReuse()
    {
        $id = $this->getRequestValue('actionElementId');
        $value   = $this->getRequestValue('value');
        $serverType   = $this->getRequestValue('mg-action');


        Machine::createOrUpdateSetting($id, Machine::REUSE, $value);
        Machine::createOrUpdateSetting($id, Machine::SERVER, $serverType);
    }
}