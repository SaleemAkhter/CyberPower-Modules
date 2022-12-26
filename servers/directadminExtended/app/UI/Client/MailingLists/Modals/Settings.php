<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\MailingLists\Modals;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\MailingLists\Forms;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Modals\ModalTabsEdit;

class Settings extends ModalTabsEdit implements ClientArea
{
    protected $id    = 'settingsModal';
    protected $name  = 'settingsModal';
    protected $title = 'settingsModal';

    public function initContent()
    {
        $this->addForm(new Forms\Settings());
    }
}
