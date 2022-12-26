<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Applications\Forms;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\BaseForm;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Fields\Hidden;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\FormConstants;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Applications\Providers;

class DeleteBackup extends BaseForm implements ClientArea
{
    protected $id    = 'deleteBackupAppForm';
    protected $name  = 'deleteBackupAppForm';
    protected $title = 'deleteBackupAppForm';

    public function initContent()
    {
        $this->setFormType(FormConstants::DELETE);
        $this->setProvider(new Providers\ApplicationsBackup());

        $this->addLangReplacements();
        $this->setConfirmMessage('confirmDeleteBackup', ['extension' => null]);

        $id = new Hidden();
        $id->initIds('id');

        $action = new Hidden();
        $action->initIds('action')
                ->setDefaultValue('deleteBackup');

        $this->addField($id)
                ->addField($action)
                ->loadDataToForm();
    }
}
