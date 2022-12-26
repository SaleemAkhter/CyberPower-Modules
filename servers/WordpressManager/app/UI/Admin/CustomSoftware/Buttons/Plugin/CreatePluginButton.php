<?php

namespace ModulesGarden\WordpressManager\App\UI\Admin\CustomSoftware\Buttons\Plugin;

use \ModulesGarden\WordpressManager\Core\UI\Interfaces\AdminArea;
use \ModulesGarden\WordpressManager\Core\UI\Widget\Buttons\ButtonCreate;
use \ModulesGarden\WordpressManager\App\UI\Admin\CustomSoftware\Modals\Plugin\CreatePluginModal;

class CreatePluginButton extends ButtonCreate implements AdminArea
{

    public function initContent()
    {
        $this->initIds('createPluginButton');
        $this->initLoadModalAction(new CreatePluginModal());
    }
}