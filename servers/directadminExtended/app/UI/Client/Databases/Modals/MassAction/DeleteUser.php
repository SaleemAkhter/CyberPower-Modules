<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Databases\Modals\MassAction;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Modals\BaseModal;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Databases\Forms;

class DeleteUser extends BaseModal implements ClientArea
{
    protected $id    = 'massDeleteUserModal';
    protected $name  = 'massDeleteUserModal';
    protected $title = 'massDeleteUserModal';

    public function initContent()
    {
        $this->setModalTitleTypeDanger()
            ->setSubmitButtonClassesDanger()
            ->addForm(new Forms\MassAction\DeleteUser());
    }
}
