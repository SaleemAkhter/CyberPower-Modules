<?php

namespace ModulesGarden\DirectAdminExtended\App\UI\Admin\Features\Modals;

use ModulesGarden\DirectAdminExtended\Core\UI\Widget\Modals\BaseModal;
use ModulesGarden\DirectAdminExtended\Core\UI\Interfaces\AdminArea;
use ModulesGarden\DirectAdminExtended\App\UI\Admin\Features\Forms;

class DeleteBackup extends BaseModal implements AdminArea
{
    protected $id    = 'deleteBackup';
    protected $name  = 'deleteBackup';
    protected $title = 'deleteBackup';

    public function initContent()
    {
        $this->setSubmitButtonClassesDanger()->setModalTitleTypeDanger();
        $this->addForm(new Forms\DeleteBackup());
    }
}
