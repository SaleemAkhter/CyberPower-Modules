<?php

namespace ModulesGarden\DirectAdminExtended\App\UI\Admin\Features\Modals;

use ModulesGarden\DirectAdminExtended\Core\UI\Widget\Modals\BaseEditModal;
use ModulesGarden\DirectAdminExtended\Core\UI\Interfaces\AdminArea;
use ModulesGarden\DirectAdminExtended\App\UI\Admin\Features\Forms;

class EditBackup extends BaseEditModal implements AdminArea
{
    protected $id    = 'editBackup';
    protected $name  = 'editBackup';
    protected $title = 'editBackup';

    public function initContent()
    {
        $this->addForm(new Forms\EditBackup());
    }
}
