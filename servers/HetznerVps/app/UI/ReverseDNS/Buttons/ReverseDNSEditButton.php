<?php

namespace ModulesGarden\Servers\HetznerVps\App\UI\ReverseDNS\Buttons;

use ModulesGarden\Servers\HetznerVps\App\UI\ReverseDNS\Modals\ReverseDNSEditModal;
use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\Buttons\ButtonDataTableModalAction;

class ReverseDNSEditButton extends ButtonDataTableModalAction implements ClientArea, AdminArea
{
    protected $id               = 'reverseDNSEditButton';
    protected $name             = 'reverseDNSEditButton';
    protected $title            = 'reverseDNSEditButton';
    protected $icon             = 'lu-icon lu-zmdi lu-zmdi-edit';
    protected $class            = ['lu-btn lu-btn--sm lu-btn--link lu-btn--icon lu-btn--plain lu-tooltip'];

    public function initContent()
    {
        $this->initLoadModalAction(new ReverseDNSEditModal());
    }
}