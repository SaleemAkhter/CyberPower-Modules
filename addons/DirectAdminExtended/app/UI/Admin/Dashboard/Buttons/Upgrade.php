<?php

namespace ModulesGarden\DirectAdminExtended\App\UI\Admin\Dashboard\Buttons;

use ModulesGarden\DirectAdminExtended\Core\UI\Interfaces\AdminArea;
use ModulesGarden\DirectAdminExtended\App\UI\Admin\Dashboard\Modals;
use ModulesGarden\DirectAdminExtended\Core\UI\Widget\Buttons\ButtonDataTableModalAction;

class Upgrade extends ButtonDataTableModalAction implements AdminArea
{
    protected $id    = 'upgradeButton';
    protected $name  = 'upgradeButton';
    protected $title = 'upgradeButton';
    protected $class = ['lu-btn lu-btn--sm lu-btn--link lu-btn--icon lu-btn--plain lu-tooltip '];
    protected $icon = 'icon-in-button lu-zmdi lu-zmdi-long-arrow-up';
    
    public function initContent()
    {
        $this->initLoadModalAction(new Modals\Upgrade());
        $this->setDisableByColumnValue('serverType', 'DirectAdmin Extended');
    }    
}
