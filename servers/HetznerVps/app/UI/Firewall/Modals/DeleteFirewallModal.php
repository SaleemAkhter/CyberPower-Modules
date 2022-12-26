<?php


namespace ModulesGarden\Servers\HetznerVps\App\UI\Firewall\Modals;

use ModulesGarden\Servers\HetznerVps\App\UI\Firewall\Forms\DeleteFirewallForm;
use ModulesGarden\Servers\HetznerVps\App\UI\Snapshots\Modals\DeleteModal;
use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\ClientArea;

class DeleteFirewallModal extends DeleteModal implements ClientArea, AdminArea
{
    protected $id               = 'deleteFirewallModal';
    protected $name             = 'deleteFirewallModal';
    protected $title            = 'deleteFirewallModal';

    public function initContent()
    {
        $this->addForm(new DeleteFirewallForm());
        $this->setSubmitButtonClassesDanger();
        $this->setModalTitleTypeDanger();
    }
}