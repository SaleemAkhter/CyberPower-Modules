<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Backups\Forms;

use \ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\BaseForm;
use \ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Fields\Hidden;
use \ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\FormConstants;
use \ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;

class Delete extends BaseForm implements ClientArea
{
    protected $id    = 'deleteForm';
    protected $name  = 'deleteForm';
    protected $title = 'deleteForm';

    public function initContent()
    {
        $this->setFormType(FormConstants::DELETE)
                ->setProvider(new \ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Backups\Providers\Backups())
                ->setConfirmMessage('confirmBackupDelete');

        $field = new Hidden('backup');

        $this->addField($field);
        $this->loadDataToForm();
    }
}
