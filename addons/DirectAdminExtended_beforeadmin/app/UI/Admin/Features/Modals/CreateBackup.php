<?php

namespace ModulesGarden\DirectAdminExtended\App\UI\Admin\Features\Modals;

use ModulesGarden\DirectAdminExtended\Core\UI\Widget\Modals\BaseEditModal;
use ModulesGarden\DirectAdminExtended\Core\UI\Interfaces\AdminArea;
use ModulesGarden\DirectAdminExtended\App\UI\Admin\Features\Forms;

class CreateBackup extends BaseEditModal implements AdminArea
{
    protected $id    = 'createBackup';
    protected $name  = 'createBackup';
    protected $title = 'createBackup';

    public function initContent()
    {
        $this->addForm(new Forms\CreateBackup());
    }
}
