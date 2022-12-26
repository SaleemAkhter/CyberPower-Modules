<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\FileManager\Modals;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Modals\BaseEditModal;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\FileManager\Forms;

class Permissions extends BaseEditModal implements ClientArea
{
    protected $id    = 'permissionsModal';
    protected $name  = 'permissionsModal';
    protected $title = 'permissionsModal';

    public function initContent()
    {
        $this->addForm(new Forms\Permissions());
    }
}
