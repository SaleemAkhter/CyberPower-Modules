<?php

namespace ModulesGarden\WordpressManager\App\UI\Admin\CustomSoftware;

use ModulesGarden\WordpressManager\Core\UI\Widget\Buttons\ButtonSwitchAjax;
use \ModulesGarden\WordpressManager\Core\UI\Interfaces\AdminArea;

class EnableThemeSwitch extends ButtonSwitchAjax implements AdminArea
{
    protected $switchModel    = '\ModulesGarden\WordpressManager\App\Models\CustomTheme';
    protected $switchColumn   = 'enable';
    protected $switchOnValue  = 1;
    protected $switchOffValue = 0;
    
}
