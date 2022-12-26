<?php

namespace ModulesGarden\DirectAdminExtended\App\UI\Admin\Features\Buttons;

use ModulesGarden\DirectAdminExtended\Core\UI\Widget\Buttons\ButtonDataTableModalAction;
use ModulesGarden\DirectAdminExtended\Core\UI\Interfaces\AdminArea;
use ModulesGarden\DirectAdminExtended\App\UI\Admin\Features\Modals;

class EditBackup extends ButtonDataTableModalAction implements AdminArea
{
    protected $id    = 'editBackup';
    protected $name  = 'editBackup';
    protected $title = 'editBackup';
    protected $class = ['lu-btn lu-btn--sm lu-btn--link lu-btn--icon lu-btn--plain lu-btn--default lu-tooltip '];
    protected $icon = 'icon-in-button lu-zmdi lu-zmdi-edit';
    
    public function initContent()
    {
        $this->initLoadModalAction(new Modals\EditBackup());
    }    
}
