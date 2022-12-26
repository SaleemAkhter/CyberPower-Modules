<?php


namespace ModulesGarden\Servers\HetznerVps\App\UI\Firewall\Modals;

use ModulesGarden\Servers\HetznerVps\App\UI\Firewall\Forms\EditFirewallForm;
use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\Modals\BaseEditModal;

class EditFirewallModal extends BaseEditModal implements ClientArea, AdminArea
{
    protected $id               = 'editFirewallModal';
    protected $name             = 'editFirewallModal';
    protected $title            = 'editFirewallModal';

    public function initContent()
    {
        $this->addForm(new EditFirewallForm());
    }
}