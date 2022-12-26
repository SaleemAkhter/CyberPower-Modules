<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Applications\Modals;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Modals\BaseModal;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Applications\Forms;

class CreateBackup extends BaseModal implements ClientArea
{
    protected $id    = 'backupAppModal';
    protected $name  = 'backupAppModal';
    protected $title = 'backupAppModal';

    public function initContent()
    {
        $this->addForm(new Forms\CreateBackup());
    }
}
