<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Snapshots\Modals;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Snapshots\Forms\Restore;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Widget\Modals\BaseModal;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Widget\Modals\ModalConfirmDanger;

/**
 * Description of Restore
 *
 * @author Mateusz Pawlowski <mateusz.pawlowski94@onet.pl>
 */
class ConfirmRestore extends ModalConfirmDanger implements ClientArea, AdminArea
{

    protected $id    = 'confirmRestoreModal';
    protected $name  = 'confirmRestoreModal';
    protected $title = 'confirmRestoreModal';

    public function initContent()
    {
        $this->setModalSizeLarge();
        $this->addForm(new Restore());
    }

}
