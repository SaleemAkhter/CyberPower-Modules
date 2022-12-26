<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Home\Modals;


use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Home\Forms\PowerOnAction;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Widget\Modals\BaseEditModal;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Widget\Modals\BaseModal;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Widget\Modals\ModalConfirmSuccess;

/**
 * Description of Restore
 *
 * @author Mateusz Pawlowski <mateusz.pawlowski94@onet.pl>
 */
class PowerOnConfirm extends ModalConfirmSuccess implements ClientArea, AdminArea
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
