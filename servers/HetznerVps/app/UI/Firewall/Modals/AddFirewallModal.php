<?php


namespace ModulesGarden\Servers\HetznerVps\App\UI\Firewall\Modals;

use ModulesGarden\Servers\HetznerVps\App\UI\Firewall\Forms\AddFirewallForm;
use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\Modals\BaseEditModal;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\Modals\BaseModal;

class AddFirewallModal extends BaseEditModal implements ClientArea, AdminArea
{
    protected $id               = 'addFirewallModal';
    protected $name             = 'addFirewallModal';
    protected $title            = 'addFirewallModal';

    public function initContent()
    {
        $this->addForm(new AddFirewallForm());
    }
}