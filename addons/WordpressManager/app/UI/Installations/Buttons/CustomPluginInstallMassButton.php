<?php

namespace ModulesGarden\WordpressManager\App\UI\Installations\Buttons;

use ModulesGarden\WordpressManager\Core\UI\Interfaces\ClientArea;
use ModulesGarden\WordpressManager\Core\UI\Widget\Buttons\ButtonMassAction;
use ModulesGarden\WordpressManager\App\UI\Installations\Modals\InstallCustomMassModal;

class CustomPluginInstallMassButton extends ButtonMassAction implements ClientArea
{
    protected $icon  = 'lu-btn__icon lu-zmdi lu-zmdi-plus';

    public function initContent()
    {
        $this->initIds('customPluginInstallMassButton');
        $this->initLoadModalAction(new InstallCustomMassModal());
    }
}