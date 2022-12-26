<?php

namespace ModulesGarden\Servers\GoogleCloudVirtualMachines\App\UI\Client\Snapshots\Modals\MassAction;

use \ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Widget\Modals\BaseModal;
use \ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\App\UI\Client\Snapshots\Forms\MassAction\DeleteMassSnapshots;
use \ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Interfaces\AdminArea;
/**
 * Description of DeleteSnapshot
 *
 * @author Kamil
 */
class DeleteSnapshots extends BaseModal implements ClientArea, AdminArea  {
    protected $id    = 'deleteSnapshotModal';
    protected $name  = 'deleteSnapshotModal';
    protected $title = 'deleteSnapshotModal';

    public function initContent()
    {
//        $this->setConfirmButtonDanger();
        $this->addForm(new DeleteMassSnapshots()); 
    }

}
