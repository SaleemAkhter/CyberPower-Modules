<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Home\Modals;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Home\Forms\RescueAction;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Widget\Modals\ModalConfirmDanger;

/**
 * Description of Restore
 *
 * @author Mateusz Pawlowski <mateusz.pawlowski94@onet.pl>
 */
class RescueConfirm extends ModalConfirmDanger implements ClientArea, AdminArea
{

    protected $id    = 'rescueConfirmModal';
    protected $name  = 'rescueConfirmModal';
    protected $title = 'rescueConfirmModal';

    public function initContent()
    {
        $this->addForm(new RescueAction());
    }
}
