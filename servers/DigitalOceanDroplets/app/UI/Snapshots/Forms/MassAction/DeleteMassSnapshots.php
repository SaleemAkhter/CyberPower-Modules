<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Snapshots\Forms\MassAction;

use ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Snapshots\Providers\Snapshot;
use \ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Widget\Forms\BaseForm;
use \ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Interfaces\ClientArea;
use \ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Widget\Forms\FormConstants;
use \ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Interfaces\AdminArea;

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
