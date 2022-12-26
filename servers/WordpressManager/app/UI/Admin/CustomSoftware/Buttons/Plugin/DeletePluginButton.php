<?php

namespace ModulesGarden\WordpressManager\App\UI\Admin\CustomSoftware\Buttons\Plugin;

use \ModulesGarden\WordpressManager\Core\UI\Interfaces\AdminArea;
use \ModulesGarden\WordpressManager\Core\UI\Widget\Buttons\ButtonDataTableModalAction;
use \ModulesGarden\WordpressManager\App\UI\Admin\CustomSoftware\Modals\Plugin\DeletePluginModal;

class DeletePluginButton extends ButtonDataTableModalAction implements AdminArea
{
    public function initContent()
    {
        $this->initIds('deletePluginButton');
        $this->initLoadModalAction(new DeletePluginModal());
        
        $this->switchToRemoveBtn();
    }
}
