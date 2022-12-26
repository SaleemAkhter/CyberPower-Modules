<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Home\Modals;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Home\Forms\ShutdownAction;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Widget\Modals\BaseModal;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Widget\Modals\ModalConfirmDanger;

/**
 * Description of Restore
 *
 * @author Mateusz Pawlowski <mateusz.pawlowski94@onet.pl>
 * @deprecated
 */
class ShutdownConfirm extends ModalConfirmDanger implements ClientArea, AdminArea
{

    protected $id    = 'shutdownConfirmModal';
    protected $name  = 'shutdownConfirmModal';
    protected $title = 'shutdownConfirmModal';

    public function initContent()
    {
        $this->setModalSizeLarge();
        $this->addForm(new ShutdownAction());
    }
}
