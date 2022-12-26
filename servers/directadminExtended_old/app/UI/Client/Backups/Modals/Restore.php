<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Backups\Modals;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Backups\Forms;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Modals\BaseModal;

class Restore extends BaseModal implements ClientArea
{
    protected $id    = 'restoreModal';
    protected $name  = 'restoreModal';
    protected $title = 'restoreModal';

    public function initContent()
    {
        $this->addForm(new Forms\Restore());
    }
}
