<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Applications\Forms;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\BaseForm;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Fields\Hidden;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\FormConstants;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Applications\Providers;

class RestoreBackup extends BaseForm implements ClientArea
{
    protected $id    = 'restoreBackupAppForm';
    protected $name  = 'restoreBackupAppForm';
    protected $title = 'restoreBackupAppForm';

    public function initContent()
    {
        $this->setFormType(FormConstants::UPDATE);
        $this->setProvider(new Providers\ApplicationsBackup());

        $this->addLangReplacements();
        $this->setConfirmMessage('confirmAppBackup', ['extension' => null]);

        $id = new Hidden('id');

        $this->addField($id)
                ->loadDataToForm();
    }
}
