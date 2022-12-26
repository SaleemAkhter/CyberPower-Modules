<?php


namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Ssh\Forms;


use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Ssh\Providers\SshProvider;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\BaseForm;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Fields\Hidden;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\FormConstants;

class AuthorizeSSHKeyForm extends BaseForm implements ClientArea
{
    protected $id    = 'authorizeSSHKeyForm';
    protected $name  = 'authorizeSSHKeyForm';
    protected $title = 'authorizeSSHKeyForm';

    public function initContent()
    {
        $this->setFormType(FormConstants::AUTHORIZE)
            ->setProvider(new SshProvider())
            ->setConfirmMessage('authorizeSSHKey');

        $fingerprint = (new Hidden('fingerprint'));

        $this->addField($fingerprint)
            ->loadDataToForm();

    }
}