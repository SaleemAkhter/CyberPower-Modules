<?php

namespace ModulesGarden\Servers\GoogleCloudVirtualMachines\App\UI\Client\Snapshots\Modals;

use ModulesGarden\Servers\GoogleCloudVirtualMachines\App\UI\Client\Snapshots\Forms\DeleteSnapshot;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Widget\Modals\BaseModal;

/**
 * Description of Restore
 *
 * @author Mateusz Pawlowski <mateusz.pawlowski94@onet.pl>
 */
class ConfirmDelete extends BaseModal implements ClientArea, AdminArea
{

    protected $id    = 'confirmDeleteModal';
    protected $name  = 'confirmDeleteModal';
    protected $title = 'confirmDeleteModal';

    public function initContent()
    {
//        $this->setModalSizeMedium();
//        $this->setConfirmButtonDanger();
        $this->addForm(new DeleteSnapshot());
    }

}
