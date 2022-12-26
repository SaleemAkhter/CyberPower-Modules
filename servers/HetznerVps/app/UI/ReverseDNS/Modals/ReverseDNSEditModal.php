<?php

namespace ModulesGarden\Servers\HetznerVps\App\UI\ReverseDNS\Modals;


use ModulesGarden\Servers\HetznerVps\App\UI\ReverseDNS\Forms\ReverseDNSEditForm;
use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\Modals\BaseEditModal;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\Modals\BaseModal;

class ReverseDNSEditModal extends BaseEditModal implements ClientArea, AdminArea
{
    protected $id               = 'reverseDNSEditModal';
    protected $name             = 'reverseDNSEditModal';
    protected $title            = 'reverseDNSEditModal';

    public function initContent()
    {
        $this->addForm(new ReverseDNSEditForm());
    }

}