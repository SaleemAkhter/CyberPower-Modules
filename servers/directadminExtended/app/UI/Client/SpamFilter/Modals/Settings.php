<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\SpamFilter\Modals;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\SpamFilter\Forms;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Modals\BaseEditModal;

class Settings extends BaseEditModal implements ClientArea
{
    protected $id    = 'settingsModal';
    protected $name  = 'settingsModal';
    protected $title = 'settingsModal';

    public function initContent()
    {
        $this->addForm(new Forms\Settings());
    }
}
