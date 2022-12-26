<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Home\Modals;

use ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Home\Forms\PowerOffAction;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Widget\Modals\BaseModal;

/**
 * Description of Restore
 *
 * @author Mateusz Pawlowski <mateusz.pawlowski94@onet.pl>
 */
class PowerOffConfirm extends BaseModal implements ClientArea, AdminArea
{

    protected $id    = 'powerOffConfirmModal';
    protected $name  = 'powerOffConfirmModal';
    protected $title = 'powerOffConfirmModal';

    public function initContent()
    {
        $this->setModalSizeLarge();
        $this->setConfirmButtonDanger();
        $this->addForm(new PowerOffAction());
    }

}
