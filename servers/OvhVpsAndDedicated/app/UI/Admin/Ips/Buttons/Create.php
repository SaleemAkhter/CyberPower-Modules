<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Ips\Buttons;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Ips\Modals\Create as CreateModal;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Widget\Buttons\ButtonModal;

/**
 * Class Create
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class Create extends ButtonModal implements ClientArea, AdminArea
{
    protected $id             = 'createIp';

    public function initContent()
    {
        $this->initLoadModalAction(new CreateModal());
    }
}