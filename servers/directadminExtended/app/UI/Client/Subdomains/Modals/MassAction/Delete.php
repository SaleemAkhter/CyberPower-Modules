<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Subdomains\Modals\MassAction;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Buttons\ButtonMassAction;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Subdomains\Forms;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Modals\BaseModal;

class Delete extends BaseModal implements ClientArea
{
    protected $id    = 'massDeleteModal';
    protected $name  = 'massDeleteModal';
    protected $title = 'massDeleteModal';

    public function initContent()
    {

        $this->setSubmitButtonClassesDanger()
            ->setModalTitleTypeDanger()
            ->addForm(new Forms\MassAction\Delete());
    }
}
