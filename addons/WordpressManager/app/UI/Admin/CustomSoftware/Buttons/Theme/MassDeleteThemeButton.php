<?php

namespace ModulesGarden\WordpressManager\App\UI\Admin\CustomSoftware\Buttons\Theme;

use \ModulesGarden\WordpressManager\Core\UI\Interfaces\AdminArea;
use ModulesGarden\WordpressManager\Core\UI\Widget\Buttons\ButtonMassAction;
use \ModulesGarden\WordpressManager\App\UI\Admin\CustomSoftware\Modals\Theme\MassDeleteThemeModal;

class MassDeleteThemeButton extends ButtonMassAction implements AdminArea
{
    public function initContent()
    {
        $this->initIds('massDeleteThemeButton');
        $this->initLoadModalAction(new MassDeleteThemeModal());
        
        $this->switchToRemoveBtn();
    }
}

