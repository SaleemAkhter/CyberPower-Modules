<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Home\Modals;

use ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Home\Forms\PowerOnAction;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Widget\Modals\BaseModal;

/**
 * Description of Restore
 *
 * @author Mateusz Pawlowski <mateusz.pawlowski94@onet.pl>
 */
class PowerOnConfirm extends BaseModal implements ClientArea, AdminArea
{

    protected $id    = 'powerOnConfirmModal';
    protected $name  = 'powerOnConfirmModal';
    protected $title = 'powerOnConfirmModal';

    public function initContent()
    {
        $this->setModalSizeLarge();
        $this->addForm(new PowerOnAction());
    }

}
