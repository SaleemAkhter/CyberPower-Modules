<?php


namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\MailingLists\Modals\MassAction;


use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Modals\BaseModal;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\MailingLists\Forms;

class DeleteSub extends BaseModal implements ClientArea
{
    protected $id    = 'massDeleteSubModal';
    protected $name  = 'massDeleteSubModal';
    protected $title = 'massDeleteSubModal';

    public function initContent()
    {
        $this->setSubmitButtonClassesDanger();
        $this->setModalTitleTypeDanger();
        $this->addForm(new Forms\MassAction\DeleteSub());
    }
}