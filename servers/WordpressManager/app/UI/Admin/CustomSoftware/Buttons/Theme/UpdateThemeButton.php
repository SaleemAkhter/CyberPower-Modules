<?php

namespace ModulesGarden\WordpressManager\App\UI\Admin\CustomSoftware\Buttons\Theme;

use \ModulesGarden\WordpressManager\Core\UI\Interfaces\AdminArea;
use \ModulesGarden\WordpressManager\Core\UI\Widget\Buttons\ButtonDataTableModalAction;
use \ModulesGarden\WordpressManager\App\UI\Admin\CustomSoftware\Modals\Theme\UpdateThemeModal;

class UpdateThemeButton extends ButtonDataTableModalAction implements AdminArea
{
    public function initContent()
    {
        $this->initIds('updateThemeButton');
        $this->initLoadModalAction(new UpdateThemeModal());
    }
}
