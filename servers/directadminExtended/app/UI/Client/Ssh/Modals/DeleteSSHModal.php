<?php


namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Ssh\Modals;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Modals\BaseModal;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Ssh\Forms\DeleteSSHForm;


class DeleteSSHModal extends BaseModal implements ClientArea
{
    protected $id    = 'deleteModal';
    protected $name  = 'deleteModal';
    protected $title = 'deleteModal';

    public function initContent()
    {
        $this->setSubmitButtonClassesDanger()
            ->setModalTitleTypeDanger()
            ->addForm(new DeleteSSHForm());
    }
}