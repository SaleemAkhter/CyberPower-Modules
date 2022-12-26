<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Home\Modals\Dedicated;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Home\Forms\Dedicated\ReinstallAction;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Widget\Modals\BaseModal;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Widget\Modals\ModalConfirmDanger;

/**
 * Description of Restore
 *
 * @author Mateusz Pawlowski <mateusz.pawlowski94@onet.pl>
 */
class ReinstallConfirm extends ModalConfirmDanger implements ClientArea, AdminArea
{
    protected $id    = 'reinstallConfirmModal';
    protected $name  = 'reinstallConfirmModal';
    protected $title = 'reinstallConfirmModal';

    public function initContent()
    {
        $this->setModalSizeLarge();
        $this->addForm(new ReinstallAction());
    }

}
