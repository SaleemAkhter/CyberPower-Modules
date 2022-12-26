<?php


namespace ModulesGarden\Servers\HetznerVps\App\UI\Firewall\Buttons;


use ModulesGarden\Servers\HetznerVps\App\UI\Firewall\Modals\AddFirewallModal;
use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\Buttons\ButtonCreate;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\Buttons\ButtonModal;

class AddFirewallButton extends ButtonCreate implements ClientArea, AdminArea
{
    protected $id               = 'addFirewallButton';
    protected $name             = 'addFirewallButton';
    protected $title            = 'addFirewallButton';
    protected $class            = ['lu-btn lu-btn--primary'];
    protected $icon             = 'lu-icon lu-zmdi lu-zmdi-plus';


    public function initContent()
    {
        $this->initLoadModalAction(new AddFirewallModal());
    }
}