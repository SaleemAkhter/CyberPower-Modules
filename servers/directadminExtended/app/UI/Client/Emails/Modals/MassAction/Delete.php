<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Emails\Modals\MassAction;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Modals\BaseModal;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Emails\Forms;

class Delete extends BaseModal implements ClientArea
{
    protected $id    = 'deleteManyModal';
    protected $name  = 'deleteManyModal';
    protected $title = 'deleteManyModal';

    public function initContent()
    {
        $this->setModalTitleTypeDanger()
            ->setSubmitButtonClassesDanger()
            ->addForm(new Forms\MassAction\Delete());

    }
}