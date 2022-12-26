<?php


namespace ModulesGarden\Servers\HetznerVps\App\UI\ReverseDNS\Buttons;


use ModulesGarden\Servers\HetznerVps\App\UI\ReverseDNS\Modals\AddReverseDNSModal;
use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\Buttons\ButtonCreate;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\Buttons\ButtonModal;

class AddReverseDNSButton extends ButtonCreate implements ClientArea, AdminArea
{
    protected $id               = 'addReverseDNSButton';
    protected $name             = 'addReverseDNSButton';
    protected $title            = 'addReverseDNSButton';
    protected $class            = ['lu-btn lu-btn--primary'];
    protected $icon             = 'lu-icon lu-zmdi lu-zmdi-plus';


    public function initContent()
    {
        $this->initLoadModalAction(new AddReverseDNSModal());
    }
}