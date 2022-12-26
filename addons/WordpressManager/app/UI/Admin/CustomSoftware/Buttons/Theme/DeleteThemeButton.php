<?php

namespace ModulesGarden\WordpressManager\App\UI\Admin\CustomSoftware\Buttons\Theme;

use \ModulesGarden\WordpressManager\Core\UI\Interfaces\AdminArea;
use \ModulesGarden\WordpressManager\Core\UI\Widget\Buttons\ButtonDataTableModalAction;
use \ModulesGarden\WordpressManager\App\UI\Admin\CustomSoftware\Modals\Theme\DeleteThemeModal;

class DeleteThemeButton extends ButtonDataTableModalAction implements AdminArea
{
    public function initContent()
    {
        $this->initIds('deleteThemeButton');
        $this->initLoadModalAction(new DeleteThemeModal());
        
        $this->switchToRemoveBtn();
    }
}
