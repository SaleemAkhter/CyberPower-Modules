<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Home\Modals;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Home\Forms\UnrescueAction;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Widget\Modals\ModalConfirmDanger;

/**
 * Description of Restore
 *
 * @author Mateusz Pawlowski <mateusz.pawlowski94@onet.pl>
 */
class UnrescueConfirm extends ModalConfirmDanger implements ClientArea, AdminArea
{

    protected $id    = 'unrescueConfirmModal';
    protected $name  = 'unrescueConfirmModal';
    protected $title = 'unrescueConfirmModal';

    public function initContent()
    {
        $this->addForm(new UnrescueAction());
    }
}
