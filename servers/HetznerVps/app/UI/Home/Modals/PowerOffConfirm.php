<?php

namespace ModulesGarden\Servers\HetznerVps\App\UI\Home\Modals;

use ModulesGarden\Servers\HetznerVps\App\UI\Home\Forms\PowerOffAction;
use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\Modals\BaseModal;

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
        $this->setModalTitleTypeDanger();
        $this->setSubmitButtonClassesDanger();
        $this->addForm(new PowerOffAction());
    }

}
