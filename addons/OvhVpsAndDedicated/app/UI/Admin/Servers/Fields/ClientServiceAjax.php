<?php

namespace ModulesGarden\OvhVpsAndDedicated\App\UI\Admin\Servers\Fields;

use ModulesGarden\OvhVpsAndDedicated\Core\UI\Interfaces\AdminArea;
use ModulesGarden\OvhVpsAndDedicated\Core\UI\Widget\Forms\AjaxFields\Select;
use ModulesGarden\OvhVpsAndDedicated\App\Libs\Repository\Manage\Service;

/**
 * Class ClientRemoteSearch
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class ClientServiceAjax extends Select implements AdminArea
{
    protected $id   = 'clientRemoteSearch';
    protected $name = 'clientRemoteSearch';

    public function prepareAjaxData()
    {
        $clientId = $this->getRequestValue('clientRemoteSearch');
        $data = Service::getAllOvhByClient($clientId)->toArray();
        $this->setAvailableValues($data);

        $selectedValue = (string) $data[0]['key'];
        $this->setSelectedValue($selectedValue);
    }
}
