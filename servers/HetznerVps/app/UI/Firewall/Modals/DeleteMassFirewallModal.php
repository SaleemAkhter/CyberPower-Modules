<?php


namespace ModulesGarden\Servers\HetznerVps\App\UI\Firewall\Modals;

use ModulesGarden\Servers\HetznerVps\App\UI\Firewall\Forms\DeleteMassFirewallForm;
use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\Modals\BaseModal;

class DeleteMassFirewallModal extends BaseModal implements ClientArea, AdminArea
{
    protected $id       = 'deleteMassFirewallModal';
    protected $name     = 'deleteMassFirewallModal';
    protected $title    = 'deleteMassFirewallModal';

    public function initContent()
    {
        $this->setModalSizeMedium();
        $this->setModalTitleTypeDanger();
        $this->setSubmitButtonClassesDanger();
        $this->addElement(new \ModulesGarden\Servers\HetznerVps\Core\UI\Builder\BaseContainer());
        $this->addForm(new DeleteMassFirewallForm());
    }
}