<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\SshKey\Modals;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Modals\BaseEditModal;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\SshKey\Forms;

class Paste extends BaseEditModal implements ClientArea
{
    protected $id    = 'pasteModal';
    protected $name  = 'pasteModal';
    protected $title = 'pasteModal';

    public function initContent()
    {
        $this->addForm(new Forms\Paste());
    }
}
