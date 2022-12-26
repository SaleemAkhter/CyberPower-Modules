<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\UserManager\Modals\MassAction;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Modals\BaseModal;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\UserManager\Forms;

class Delete extends BaseModal implements ClientArea
{
    protected $id    = 'massDeleteModal';
    protected $name  = 'massDeleteModal';
    protected $title = 'massDeleteModal';

    public function initContent()
    {
        $this->setSubmitButtonClassesDanger();
        $this->setModalTitleTypeDanger();
        $this->addForm(new Forms\MassAction\Delete());
    }
}
