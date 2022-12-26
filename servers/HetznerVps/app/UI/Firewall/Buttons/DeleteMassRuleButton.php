<?php


namespace ModulesGarden\Servers\HetznerVps\App\UI\Firewall\Buttons;

use ModulesGarden\Servers\HetznerVps\App\UI\Firewall\Modals\DeleteMassRuleModal;
use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\Buttons\ButtonMassAction;

class DeleteMassRuleButton extends ButtonMassAction implements ClientArea, AdminArea
{
    protected $id               = 'deleteMassRuleButton';
    protected $name             = 'deleteMassRuleButton';
    protected $title            = 'deleteMassRuleButton';
//    protected $icon             = 'lu-zmdi lu-zmdi-delete';

    public function initContent()
    {
        $this->switchToRemoveBtn();
        $this->initLoadModalAction(new DeleteMassRuleModal());
    }
}