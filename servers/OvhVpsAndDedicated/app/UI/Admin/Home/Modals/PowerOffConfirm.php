<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Home\Modals;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Home\Forms\PowerOffAction;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Widget\Modals\BaseModal;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Widget\Modals\ModalConfirmDanger;

/**
 * Description of Restore
 *
 * @author Mateusz Pawlowski <mateusz.pawlowski94@onet.pl>
 */
class PowerOffConfirm extends ModalConfirmDanger implements ClientArea, AdminArea
{

    protected $id    = 'powerOffConfirmModal';
    protected $name  = 'powerOffConfirmModal';
    protected $title = 'powerOffConfirmModal';

    public function initContent()
    {
        $this->setModalSizeLarge();
        $this->addForm(new PowerOffAction());
    }

}
