<?php


namespace ModulesGarden\Servers\HetznerVps\App\UI\ReverseDNS\Modals;


use ModulesGarden\Servers\HetznerVps\App\UI\ReverseDNS\Forms\AddReverseDNSForm;
use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\Modals\BaseEditModal;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\Modals\BaseModal;

class AddReverseDNSModal extends BaseEditModal implements ClientArea, AdminArea
{
    protected $id               = 'addReverseDNSModal';
    protected $name             = 'addReverseDNSModal';
    protected $title            = 'addReverseDNSModal';

    public function initContent()
    {
        $this->addForm(new AddReverseDNSForm());
    }
}