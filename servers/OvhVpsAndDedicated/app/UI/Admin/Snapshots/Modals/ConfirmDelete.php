<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Snapshots\Modals;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Snapshots\Forms\DeleteSnapshot;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Widget\Modals\BaseModal;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Widget\Modals\ModalConfirmDanger;

/**
 * Description of Restore
 *
 * @author Mateusz Pawlowski <mateusz.pawlowski94@onet.pl>
 */
class ConfirmDelete extends ModalConfirmDanger implements ClientArea, AdminArea
{

    protected $id    = 'confirmDeleteModal';
    protected $name  = 'confirmDeleteModal';
    protected $title = 'confirmDeleteModal';

    public function initContent()
    {
        $this->setModalSizeMedium();
        $this->addForm(new DeleteSnapshot());
    }

}
