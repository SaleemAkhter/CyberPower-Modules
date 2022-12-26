<?php


namespace ModulesGarden\Servers\HetznerVps\App\UI\Firewall\Buttons;

use ModulesGarden\Servers\HetznerVps\App\UI\Firewall\Modals\DeleteRuleModal;
use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\Buttons\ButtonDataTableModalAction;

class DeleteRuleButton extends ButtonDataTableModalAction implements ClientArea, AdminArea
{
    protected $id               = 'deleteRuleButton';
    protected $name             = 'deleteRuleButton';
    protected $title            = 'deleteRuleButton';
    protected $icon             = 'lu-btn__icon lu-zmdi lu-zmdi-delete';
    protected $class            = ['lu-btn lu-btn--sm lu-btn--danger lu-btn--link lu-btn--icon lu-btn--plain'];

    public function initContent()
    {
        $this->initLoadModalAction(new DeleteRuleModal());
    }
}