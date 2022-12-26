<?php


namespace ModulesGarden\Servers\HetznerVps\App\UI\ReverseDNS\Buttons;


use ModulesGarden\Servers\HetznerVps\App\UI\ReverseDNS\Modals\DeleteReverseDNSModal;
use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\Buttons\ButtonDataTableModalAction;

class DeleteReverseDNSButton extends ButtonDataTableModalAction implements ClientArea, AdminArea
{
    protected $id               = 'deleteReverseDNSButton';
    protected $name             = 'deleteReverseDNSButton';
    protected $title            = 'deleteReverseDNSButton';
    protected $icon             = 'lu-icon lu-zmdi lu-zmdi-refresh';
    protected $class            = ['lu-btn lu-btn--sm lu-btn--danger lu-btn--link lu-btn--icon lu-btn--plain'];


    public function initContent()
    {
        $this->initLoadModalAction(new DeleteReverseDNSModal());
    }
}