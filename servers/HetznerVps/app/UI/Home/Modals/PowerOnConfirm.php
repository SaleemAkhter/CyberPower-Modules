<?php

namespace ModulesGarden\Servers\HetznerVps\App\UI\Home\Modals;

use ModulesGarden\Servers\HetznerVps\App\UI\Home\Forms\PowerOnAction;
use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\Modals\BaseModal;

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
        $this->initIds('powerOnConfirm');
        $this->setModalSizeLarge();
        $this->addForm(new PowerOnAction());
    }

}
