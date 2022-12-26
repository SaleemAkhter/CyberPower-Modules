<?php

namespace ModulesGarden\OvhVpsAndDedicated\App\UI\Admin\Servers\Providers;

use ModulesGarden\OvhVpsAndDedicated\Core\UI\Interfaces\AdminArea;
use ModulesGarden\OvhVpsAndDedicated\Core\UI\Traits\RequestObjectHandler;
use ModulesGarden\OvhVpsAndDedicated\Core\UI\Widget\Forms\DataProviders\BaseModelDataProvider;
use \ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Facade\Repository\Dedicated\Repository as DedicatedRepository;
use ModulesGarden\OvhVpsAndDedicated\App\UI\Admin\Servers\Helpers\Dedicated as DedicatedPageHelper;


/**
 * Class Dedicated
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class Dedicated extends BaseModelDataProvider implements AdminArea
{
    use RequestObjectHandler;

    public function __construct()
    {
        parent::__construct(null);
    }

    public function getDedicatedServers($clientsGroupByMachineName)
    {
        $serverId = $this->getRequestValue('serverid');
        $repository = new DedicatedRepository(['serverid' => $serverId]);

        return DedicatedPageHelper::assignExtraValues($repository->getAllToArray(), $clientsGroupByMachineName);
    }

}