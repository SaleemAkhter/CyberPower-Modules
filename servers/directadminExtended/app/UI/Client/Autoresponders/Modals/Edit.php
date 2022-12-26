<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Autoresponders\Modals;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Modals\BaseEditModal;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Autoresponders\Forms;

class Edit extends BaseEditModal implements ClientArea
{
    protected $id    = 'editModal';
    protected $name  = 'editModal';
    protected $title = 'editModal';

    public function initContent()
    {
        $this->addForm(new Forms\Edit());
    }
}
