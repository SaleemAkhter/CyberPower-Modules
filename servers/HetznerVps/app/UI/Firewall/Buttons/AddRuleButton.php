<?php


namespace ModulesGarden\Servers\HetznerVps\App\UI\Firewall\Buttons;

use ModulesGarden\Servers\HetznerVps\App\UI\Firewall\Modals\AddRuleModal;
use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\Buttons\ButtonCreate;

class AddRuleButton extends ButtonCreate implements ClientArea, AdminArea
{
    protected $id               = 'addRuleButton';
    protected $name             = 'addRuleButton';
    protected $title            = 'addRuleButton';
    protected $class            = ['lu-btn lu-btn--primary'];
    protected $icon             = 'lu-icon lu-zmdi lu-zmdi-plus';

    public function initContent()
    {
        $this->initLoadModalAction(new AddRuleModal());
    }
}