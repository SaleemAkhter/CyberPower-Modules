<?php

namespace ModulesGarden\WordpressManager\App\UI\Admin\CustomSoftware\Buttons\Plugin;

use \ModulesGarden\WordpressManager\Core\UI\Interfaces\AdminArea;
use ModulesGarden\WordpressManager\Core\UI\Widget\Buttons\ButtonMassAction;
use \ModulesGarden\WordpressManager\App\UI\Admin\CustomSoftware\Modals\Plugin\MassDeletePluginModal;

class MassDeletePluginButton extends ButtonMassAction implements AdminArea
{
    public function initContent()
    {
        $this->initIds('massDeletePluginButton');
        $this->initLoadModalAction(new MassDeletePluginModal());
        
        $this->switchToRemoveBtn();
    }
}

