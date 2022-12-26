<?php

namespace ModulesGarden\WordpressManager\App\UI\Installations\Theme;

use \ModulesGarden\WordpressManager\Core\UI\Widget\Buttons\ButtonDataTableModalAction ;
use ModulesGarden\WordpressManager\Core\UI\Interfaces\ClientArea;

class InstallCustomButton extends ButtonDataTableModalAction  implements ClientArea
{

    protected $icon  = 'lu-btn__icon lu-zmdi lu-zmdi-plus';

    public function initContent()
    {
        $this->initIds('customThemeInstallButton');
        $this->initLoadModalAction(new InstallCustomModal());
    }
}
