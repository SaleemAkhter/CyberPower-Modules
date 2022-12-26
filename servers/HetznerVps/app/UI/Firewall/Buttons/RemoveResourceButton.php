<?php


namespace ModulesGarden\Servers\HetznerVps\App\UI\Firewall\Buttons;


use ModulesGarden\Servers\HetznerVps\App\UI\Firewall\Modals\RemoveResourceModal;
use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\Traits\DisableButtonByColumnValue;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\Buttons\ButtonDataTableModalAction;

class RemoveResourceButton extends ButtonDataTableModalAction implements ClientArea, AdminArea
{
    use DisableButtonByColumnValue;

    protected $id               = 'removeResource';
    protected $class            = ['lu-btn lu-btn--sm lu-btn--link lu-btn--icon lu-btn--plain lu-tooltip'];
    protected $icon             = 'lu-zmdi lu-zmdi-refresh';
    protected $title            = 'removeResource';
    protected $customActionName = "resourceModal";

    public function initContent()
    {
        $this->setDisableByColumnValue('noAppiled', true);
        $this->initLoadModalAction(new RemoveResourceModal());
    }
}