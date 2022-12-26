<?php

namespace ModulesGarden\WordpressManager\App\UI\Installations\Buttons;

use \ModulesGarden\WordpressManager\Core\UI\Widget\Buttons\ButtonDataTableModalAction ;
use ModulesGarden\WordpressManager\Core\UI\Interfaces\ClientArea;
use ModulesGarden\WordpressManager\App\UI\Installations\Modals\CustomPluginInstallModal;

class CustomPluginInstallButton extends ButtonDataTableModalAction  implements ClientArea
{

    protected $icon  = 'lu-btn__icon lu-zmdi lu-zmdi-plus';

    public function initContent()
    {
        $this->initIds('customPluginInstallButton');
        $this->initLoadModalAction(new CustomPluginInstallModal());
    }
}
