<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Home\Modals;

use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Widget\Modals\BaseEditModal;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Widget\Modals\ModalConfirmDanger;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Home\Forms\ChangPasswordForm;
/**
 * Description of ChangePasswordConfirm
 *
 * @author Mateusz Pawlowski <mateusz.pawlowski94@onet.pl>
 */
class ChangePasswordConfirm extends ModalConfirmDanger implements ClientArea, AdminArea
{

    protected $id    = 'changePasswordModal';
    protected $name  = 'changePasswordModal';
    protected $title = 'changePasswordModal';

    public function initContent()
    {
        $this->addForm(new ChangPasswordForm());
    }

}
