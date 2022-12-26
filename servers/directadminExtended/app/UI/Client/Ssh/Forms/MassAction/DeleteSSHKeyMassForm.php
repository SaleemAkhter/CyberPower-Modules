<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Ssh\Forms\MassAction;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\BaseForm;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Ssh\Providers;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\FormConstants;

class DeleteSSHKeyMassForm extends BaseForm implements ClientArea
{
    protected $id    = 'massDeleteForm';
    protected $name  = 'massDeleteForm';
    protected $title = 'massDeleteForm';

    public function initContent()
    {
        $this->setFormType(FormConstants::DELETE)
            ->setProvider(new Providers\SshProvider())
            ->setConfirmMessage('deleteMany');
    }
}