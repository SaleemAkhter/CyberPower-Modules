<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Databases\Modals;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Modals\BaseEditModal;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Databases\Forms;

class AddUser extends BaseEditModal implements ClientArea
{
    protected $id    = 'addUserModal';
    protected $name  = 'addUserModal';
    protected $title = 'addUserModal';

    public function initContent()
    {
        $this->addForm(new Forms\AddUser());
    }
}
