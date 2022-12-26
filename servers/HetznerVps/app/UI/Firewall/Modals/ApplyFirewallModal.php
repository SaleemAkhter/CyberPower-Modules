<?php


namespace ModulesGarden\Servers\HetznerVps\App\UI\Firewall\Modals;

use ModulesGarden\Servers\HetznerVps\App\UI\Firewall\Forms\ApplyFirewallForm;
use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\Modals\BaseEditModal;

class ApplyFirewallModal extends BaseEditModal implements ClientArea, AdminArea
{
    protected $id               = 'applyFirewallModal';
    protected $name             = 'applyFirewallModal';
    protected $title            = 'applyFirewallModal';

    public function initContent()
    {
        $this->addForm(new ApplyFirewallForm());
    }
}