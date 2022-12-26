<?php

namespace ModulesGarden\Servers\GoogleCloudVirtualMachines\App\UI\Client\Snapshots\Modals;

use ModulesGarden\Servers\GoogleCloudVirtualMachines\App\UI\Client\Snapshots\Forms\RestoreSnapshot;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Widget\Modals\BaseModal;

/**
 * Description of Restore
 *
 * @author Mateusz Pawlowski <mateusz.pawlowski94@onet.pl>
 */
class ConfirmRestore extends BaseModal implements ClientArea, AdminArea
{

    protected $id    = 'confirmRestoreModal';
    protected $name  = 'confirmRestoreModal';
    protected $title = 'confirmRestoreModal';

    public function initContent()
    {
//        $this->setModalSizeLarge();
//        $this->setConfirmButtonDanger();
        $this->addForm(new RestoreSnapshot());
    }

}
