<?php


namespace ModulesGarden\Servers\HetznerVps\App\UI\Firewall\Buttons;


use ModulesGarden\Servers\HetznerVps\App\UI\Firewall\Modals\ApplyFirewallModal;
use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\Traits\DisableButtonByColumnValue;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\Buttons\ButtonDataTableModalAction;

class ApplyFirewallButton extends ButtonDataTableModalAction implements ClientArea, AdminArea
{
    use DisableButtonByColumnValue;

    protected $id               = 'applyFirewallButton';
    protected $name             = 'applyFirewallButton';
    protected $title            = 'applyFirewallButton';
    protected $icon             = 'lu-zmdi lu-zmdi-time-restore-setting';  // cion


    public function initContent()
    {
        $this->setDisableByColumnValue('appiled', 1);
        $this->initLoadModalAction(new ApplyFirewallModal());
    }
}