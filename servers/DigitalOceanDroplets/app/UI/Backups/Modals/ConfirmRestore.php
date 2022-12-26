<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Backups\Modals;

use ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Backups\Forms\ChangeKernel;
use ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Backups\Forms\RestoreDroplet;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Widget\Modals\BaseModal;

/**
 * Description of Restore
 *
 * @author Mateusz Pawlowski <mateusz.pawlowski94@onet.pl>
 */
class ConfirmRestore extends BaseModal implements ClientArea, AdminArea
{

    protected $id    = 'confirmBackupRestoreModal';
    protected $name  = 'confirmBackupRestoreModal';
    protected $title = 'confirmBackupRestoreModal';

    public function initContent()
    {
        $this->setModalSizeLarge();
        $this->setConfirmButtonDanger();
        $this->addForm(new RestoreDroplet());
    }

}
