<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Applications\Modals;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Modals\BaseModal;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Applications\Forms;

class DeleteBackup extends BaseModal implements ClientArea
{
    protected $id    = 'deleteBackupAppModal';
    protected $name  = 'deleteBackupAppModal';
    protected $title = 'deleteBackupAppModal';

    public function initContent()
    {
        $this->setModalTitleTypeDanger()
            ->setSubmitButtonClassesDanger()
            ->addForm(new Forms\DeleteBackup());
    }
}
