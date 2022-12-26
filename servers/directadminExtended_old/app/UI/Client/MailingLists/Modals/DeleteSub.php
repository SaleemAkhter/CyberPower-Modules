<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\MailingLists\Modals;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Modals\BaseModal;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\MailingLists\Forms;

class DeleteSub extends BaseModal implements ClientArea
{
    protected $id    = 'deleteSubModal';
    protected $name  = 'deleteSubModal';
    protected $title = 'deleteSubModal';

    public function initContent()
    {
        $this->setSubmitButtonClassesDanger()
            ->setModalTitleTypeDanger()
            ->addForm(new Forms\DeleteSub());
    }
}