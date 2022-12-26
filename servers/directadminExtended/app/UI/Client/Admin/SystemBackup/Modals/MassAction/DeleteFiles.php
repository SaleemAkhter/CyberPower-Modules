<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\SystemBackup\Modals\MassAction;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Modals\BaseModal;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\SystemBackup\Forms;

class DeleteFiles extends BaseModal implements ClientArea
{
    protected $id    = 'massDeleteFilesModal';
    protected $name  = 'massDeleteFilesModal';
    protected $title = 'massDeleteFilesModal';

    public function initContent()
    {
        $this->setSubmitButtonClassesDanger();
        $this->setModalTitleTypeDanger();
        $this->addForm(new Forms\MassAction\DeleteFiles());
    }
}
