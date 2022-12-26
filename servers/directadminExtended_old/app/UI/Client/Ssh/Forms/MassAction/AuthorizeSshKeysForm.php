<?php


namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Ssh\Forms\MassAction;

use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Ssh\Providers\SshProvider;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\BaseForm;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\FormConstants;

class AuthorizeSshKeysForm extends BaseForm implements ClientArea
{
    protected $id    = 'authorizeManyForm';
    protected $name  = 'authorizeManyForm';
    protected $title = 'authorizeManyForm';

    public function initContent()
    {
        $this->setFormType(FormConstants::AUTHORIZE)
            ->setProvider(new SshProvider())
            ->setConfirmMessage('authorizeMany');
    }
}