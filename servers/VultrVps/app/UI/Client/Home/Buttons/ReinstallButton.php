<?php


namespace ModulesGarden\Servers\VultrVps\App\UI\Client\Home\Buttons;


use ModulesGarden\Servers\VultrVps\App\UI\Client\Home\Modals\RebootInstance;
use ModulesGarden\Servers\VultrVps\App\UI\Client\Home\Modals\ReinstallModal;
use ModulesGarden\Servers\VultrVps\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\VultrVps\Core\UI\Interfaces\ClientArea;

class ReinstallButton extends VpsActionButton implements AdminArea, ClientArea
{

    public function initContent()
    {
        $this->initIds('reinstallButton');
        $this->setIconFileName('reinstall.png');
        $this->initLoadModalAction(new ReinstallModal());
    }
}