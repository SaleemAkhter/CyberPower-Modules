<?php

namespace ModulesGarden\WordpressManager\App\UI\Admin\CustomSoftware\Buttons\Plugin;

use \ModulesGarden\WordpressManager\Core\UI\Interfaces\AdminArea;
use \ModulesGarden\WordpressManager\Core\UI\Widget\Buttons\ButtonDataTableModalAction;
use \ModulesGarden\WordpressManager\App\UI\Admin\CustomSoftware\Modals\Plugin\UpdatePluginModal;

class UpdatePluginButton extends ButtonDataTableModalAction implements AdminArea
{
    public function initContent()
    {
        $this->initIds('updatePluginButton');
        $this->initLoadModalAction(new UpdatePluginModal());
    }
}
