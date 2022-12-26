<?php

namespace ModulesGarden\DirectAdminExtended\App\UI\Admin\Features\Buttons;

use ModulesGarden\DirectAdminExtended\Core\UI\Interfaces\AdminArea;
use ModulesGarden\DirectAdminExtended\App\UI\Admin\Features\Modals;
use ModulesGarden\DirectAdminExtended\Core\UI\Widget\Buttons\ButtonMassAction;

class MassCopy extends ButtonMassAction implements AdminArea
{
    protected $id    = 'massCopyButton';
    protected $name  = 'massCopyButton';
    protected $title = 'massCopyButton';
    protected $icon  = 'btn--icon lu-zmdi lu-zmdi-copy';
    
    public function initContent()
    {
       $this->initLoadModalAction(new Modals\CopyConfiguration());
    }    
}
