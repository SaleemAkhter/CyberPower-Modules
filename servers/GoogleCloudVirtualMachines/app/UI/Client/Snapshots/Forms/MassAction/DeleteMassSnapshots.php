<?php

namespace ModulesGarden\Servers\GoogleCloudVirtualMachines\App\UI\Client\Snapshots\Forms\MassAction;

use ModulesGarden\Servers\GoogleCloudVirtualMachines\App\UI\Client\Snapshots\Providers\Snapshot;
use \ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Widget\Forms\BaseForm;
use \ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Interfaces\ClientArea;
use \ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Widget\Forms\FormConstants;
use \ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Interfaces\AdminArea;

class DeleteMassSnapshots extends BaseForm implements ClientArea, AdminArea {
   
    protected $id    = 'deleteMassSnapshotForm';
    protected $name  = 'deleteMassSnapshotForm';
    protected $title = 'deleteMassSnapshotForm';

    public function initContent()
    {
        $this->setFormType(FormConstants::DELETE);
        $this->setProvider(new Snapshot());
        $this->setConfirmMessage('confirmMassSnapshotDelete');
        $this->loadDataToForm();
    }
    
}
