<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Databases\Modals;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Modals\BaseEditModal;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Databases\Forms;

class AddPrivileges extends BaseEditModal implements ClientArea
{
    protected $id    = 'addPrivilegesModal';
    protected $name  = 'addPrivilegesModal';
    protected $title = 'addPrivilegesModal';

    public function initContent()
    {
        $this->addForm(new Forms\AddUser());
    }
}
