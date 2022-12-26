<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\MailingLists\Modals;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Modals\BaseModal;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\MailingLists\Forms;

class Delete extends BaseModal implements ClientArea
{
    protected $id    = 'deleteModal';
    protected $name  = 'deleteModal';
    protected $title = 'deleteModal';

    public function initContent()
    {
        $this->setModalTitleTypeDanger()
            ->setSubmitButtonClassesDanger()
            ->addForm(new Forms\Delete());
    }
}
