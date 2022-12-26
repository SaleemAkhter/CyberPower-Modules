<?php

namespace ModulesGarden\WordpressManager\App\UI\Admin\CustomSoftware\Buttons\Theme;

use \ModulesGarden\WordpressManager\Core\UI\Interfaces\AdminArea;
use \ModulesGarden\WordpressManager\Core\UI\Widget\Buttons\ButtonCreate;
use \ModulesGarden\WordpressManager\App\UI\Admin\CustomSoftware\Modals\Theme\CreateThemeModal;

class CreateThemeButton extends ButtonCreate implements AdminArea
{

    public function initContent()
    {
        $this->initIds('createThemeButton');
        $this->initLoadModalAction(new CreateThemeModal());
    }
}