<?php


namespace ModulesGarden\Servers\HetznerVps\App\UI\Firewall\Buttons;

use ModulesGarden\Servers\HetznerVps\App\UI\Firewall\Modals\DeleteMassFirewallModal;
use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\Buttons\ButtonMassAction;

class DeleteMassFirewallButton extends ButtonMassAction implements ClientArea, AdminArea
{
    protected $id               = 'deleteMassFirewallButton';
    protected $name             = 'deleteMassFirewallButton';
    protected $title            = 'deleteMassFirewallButton';
//    protected $icon             = 'lu-zmdi lu-zmdi-delete';

    public function initContent()
    {
        $this->switchToRemoveBtn();
        $this->initLoadModalAction(new DeleteMassFirewallModal());
    }
}