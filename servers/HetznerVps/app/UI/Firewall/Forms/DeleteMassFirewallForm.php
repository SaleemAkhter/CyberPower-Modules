<?php


namespace ModulesGarden\Servers\HetznerVps\App\UI\Firewall\Forms;

use ModulesGarden\Servers\HetznerVps\App\UI\Firewall\Providers\FirewallProvider;
use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\Forms\BaseForm;

class DeleteMassFirewallForm extends BaseForm implements ClientArea, AdminArea
{
    protected $id       = 'deleteMassFirewallForm';
    protected $name     = 'deleteMassFirewallForm';
    protected $title    = 'deleteMassFirewallForm';

//    protected function getDefaultActions()
//    {
//        return ['deleteMass'];
//    }

    public function initContent()
    {
        $this->setFormType('deleteMass');
        $this->setProvider(new FirewallProvider());
        $this->setConfirmMessage('confirmFirewallDelete');
        $this->loadDataToForm();
    }

    public function getAllowedActions()
    {
        return ['deleteMass'];
    }
}