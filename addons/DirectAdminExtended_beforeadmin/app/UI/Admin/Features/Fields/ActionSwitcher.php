<?php

namespace ModulesGarden\DirectAdminExtended\App\UI\Admin\Features\Fields;

use ModulesGarden\DirectAdminExtended\Core\UI\Widget\Forms\Fields\Switcher;
use ModulesGarden\DirectAdminExtended\Core\UI\Interfaces\AdminArea;

class ActionSwitcher extends Switcher implements AdminArea
{
    
    protected $customActionName;
    
    public function initContent()
    {
        unset($this->htmlAttributes['data-toggle']);
    }
    

    
}
