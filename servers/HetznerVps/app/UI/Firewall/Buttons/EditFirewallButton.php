<?php


namespace ModulesGarden\Servers\HetznerVps\App\UI\Firewall\Buttons;

use ModulesGarden\Servers\HetznerVps\App\UI\Firewall\Modals\EditFirewallModal;
use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\Buttons\ButtonDataTableModalAction;

class EditFirewallButton extends ButtonDataTableModalAction implements ClientArea, AdminArea
{
    protected $id               = 'editFirewallButton';
    protected $name             = 'editFirewallButton';
    protected $class            = ['lu-btn lu-btn--sm lu-btn--link lu-btn--icon lu-btn--plain lu-tooltip'];
    protected $title            = 'editFirewallButton';
    protected $icon             = 'lu-zmdi lu-zmdi-edit';


    public function initContent()
    {
        $this->initLoadModalAction(new EditFirewallModal());
    }
}