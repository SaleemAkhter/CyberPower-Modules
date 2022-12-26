<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Backups\Forms;

use \ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\BaseForm;
use \ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Fields\Hidden;
use \ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\FormConstants;
use \ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;

class RestoreLocal extends BaseForm implements ClientArea
{
    protected $id    = 'restoreForm';
    protected $name  = 'restoreForm';
    protected $title = 'restoreForm';

    const RESTORE_LOCAL = 'restoreLocal';
    public function initContent()
    {

        $this->addDefaultActions(self::RESTORE_LOCAL);
        $this->setFormType(self::RESTORE_LOCAL)
                ->setProvider(new \ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Backups\Providers\BackupsExtended())
                ->setConfirmMessage('confirmBackupRestore');

        $fieldBackup = new Hidden('backup');
        $fieldID = new Hidden('id');

        $this->addField($fieldBackup)->addField($fieldID);

        $this->loadDataToForm();
    }
}
