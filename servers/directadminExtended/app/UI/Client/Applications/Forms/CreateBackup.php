<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Applications\Forms;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\BaseForm;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Fields\Hidden;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\FormConstants;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Applications\Providers;

class CreateBackup extends BaseForm implements ClientArea
{
    protected $id    = 'backupAppForm';
    protected $name  = 'backupAppForm';
    protected $title = 'backupAppForm';

    public function initContent()
    {
        $this->setFormType(FormConstants::CREATE);
        $this->setProvider(new Providers\ApplicationsBackup());

        $this->addLangReplacements();
        $this->setConfirmMessage('confirmAppBackup', ['extension' => null]);

        $id = new Hidden('id');

        $action = new Hidden();
        $action->initIds('action')
                ->setDefaultValue('createBackup');

        $this->addField($id)
                ->addField($action)
                ->loadDataToForm();
    }
}
