<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\FileManager\Modals\MassAction;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Modals\BaseEditModal;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\FileManager\Forms;

class Permissions extends BaseEditModal implements ClientArea
{
    protected $id    = 'massPermissionsModal';
    protected $name  = 'massPermissionsModal';
    protected $title = 'massPermissionsModal';

    public function initContent()
    {
        $this->addForm(new Forms\MassAction\Permissions());
    }
}
