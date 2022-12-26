<?php


namespace ModulesGarden\Servers\HetznerVps\App\UI\Firewall\Buttons;

use ModulesGarden\Servers\HetznerVps\App\UI\Firewall\Modals\EditRuleModal;
use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\Buttons\ButtonDataTableModalAction;

class EditRuleButton extends ButtonDataTableModalAction implements ClientArea, AdminArea
{
    protected $id       = 'editRuleButton';
    protected $name     = 'editRuleButton';
    protected $title    = 'editRuleButton';
    protected $class    = ['lu-btn lu-btn--sm lu-btn--link lu-btn--icon lu-btn--plain lu-tooltip'];
    protected $icon     = 'lu-zmdi lu-zmdi-edit';

    public function initContent()
    {
        $this->initLoadModalAction(new EditRuleModal());
    }
}