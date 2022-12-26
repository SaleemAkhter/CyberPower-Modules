<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Home\Modals;

use ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Home\Forms\ShutdownAction;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Widget\Modals\BaseModal;

/**
 * Description of Restore
 *
 * @author Mateusz Pawlowski <mateusz.pawlowski94@onet.pl>
 */
class ShutdownConfirm extends BaseModal implements ClientArea, AdminArea
{

    protected $id    = 'shutdownConfirmModal';
    protected $name  = 'shutdownConfirmModal';
    protected $title = 'shutdownConfirmModal';

    public function initContent()
    {
        $this->setModalSizeLarge();
        $this->setConfirmButtonDanger();
        $this->addForm(new ShutdownAction());
    }

}
