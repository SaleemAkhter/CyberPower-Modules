<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Home\Modals\Dedicated;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Home\Forms\Dedicated\RebootAction;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Widget\Modals\BaseEditModal;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Widget\Modals\BaseModal;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Widget\Modals\ModalConfirmDanger;

/**
 * Description of Restore
 *
 * @author Mateusz Pawlowski <mateusz.pawlowski94@onet.pl>
 */
class RebootConfirm extends ModalConfirmDanger implements ClientArea, AdminArea
{
    protected $id    = 'rebootConfirmModal';
    protected $name  = 'rebootConfirmModal';
    protected $title = 'rebootConfirmModal';

    public function initContent()
    {
        $this->setModalSizeLarge();
        $this->addForm(new RebootAction());
    }

}
