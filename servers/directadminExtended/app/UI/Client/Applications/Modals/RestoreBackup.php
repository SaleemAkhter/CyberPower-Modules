<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Applications\Modals;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Modals\BaseModal;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Applications\Forms;

class RestoreBackup extends BaseModal implements ClientArea
{
    protected $id    = 'restoreBackupAppModal';
    protected $name  = 'restoreBackupAppModal';
    protected $title = 'restoreBackupAppModal';

    public function initContent()
    {
        $this->addForm(new Forms\RestoreBackup());
    }
}
