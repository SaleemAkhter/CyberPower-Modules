<?php

namespace ModulesGarden\WordpressManager\App\UI\Installations\Theme;

use ModulesGarden\WordpressManager\Core\UI\Widget\Buttons\ButtonMassAction;
use ModulesGarden\WordpressManager\Core\UI\Interfaces\ClientArea;

class InstallCustomMassButton extends ButtonMassAction implements ClientArea
{
    protected $icon  = 'lu-btn__icon lu-zmdi lu-zmdi-plus';

    public function initContent()
    {
        $this->initIds('customThemeInstallMassButton');
        $this->initLoadModalAction(new InstallCustomMassModal());
    }
}
