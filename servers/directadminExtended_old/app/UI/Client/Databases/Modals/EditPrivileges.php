<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Databases\Modals;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Modals\BaseEditModal;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Modals\BaseModal;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Databases\Forms;

class EditPrivileges extends BaseEditModal implements ClientArea
{
    protected $id    = 'editPrivilegesModal';
    protected $name  = 'editPrivilegesModal';
    protected $title = 'editPrivilegesModal';

    /**
     *
     */
    public function initContent()
    {
        $this->addForm(new Forms\EditPrivileges());
    }
}
