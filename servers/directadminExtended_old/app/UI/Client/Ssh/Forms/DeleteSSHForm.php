<?php


namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Ssh\Forms;

use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Ssh\Providers\SshProvider;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\BaseForm;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Fields\Hidden;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\FormConstants;


class DeleteSSHForm extends BaseForm implements ClientArea
{
    protected $id    = 'deleteForm';
    protected $name  = 'deleteForm';
    protected $title = 'deleteForm';

    public function initContent()
    {
        $this->setFormType(FormConstants::DELETE)
            ->setProvider(new SshProvider())
            ->setConfirmMessage('confirmSSHDelete');

        $fingerprint = (new Hidden('fingerprint'));
        $type = (new Hidden('kind'));

        $this->addField($fingerprint)
            ->addField($type)
            ->loadDataToForm();

    }
}