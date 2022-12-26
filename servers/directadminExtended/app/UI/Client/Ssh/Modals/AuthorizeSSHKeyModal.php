<?php


namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Ssh\Modals;


use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Ssh\Forms\AuthorizeSSHKeyForm;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Modals\BaseModal;

class AuthorizeSSHKeyModal extends BaseModal implements ClientArea
{
    protected $id    = 'authorizeSSHKeyModal';
    protected $name  = 'authorizeSSHKeyModal';
    protected $title = 'authorizeSSHKeyModal';

    public function initContent()
    {
        $this->addForm(new AuthorizeSSHKeyForm());
    }
}