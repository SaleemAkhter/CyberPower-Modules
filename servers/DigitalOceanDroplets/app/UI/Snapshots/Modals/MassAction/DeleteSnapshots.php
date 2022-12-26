<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Snapshots\Modals\MassAction;

use \ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Widget\Modals\BaseModal;
use \ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Snapshots\Forms\MassAction\DeleteMassSnapshots;
use \ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Interfaces\AdminArea;
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
        $this->setConfirmButtonDanger();
        $this->addForm(new DeleteMassSnapshots()); 
    }

}
