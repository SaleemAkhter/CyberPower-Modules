<?php


namespace ModulesGarden\Servers\HetznerVps\App\UI\Firewall\Modals;

use ModulesGarden\Servers\HetznerVps\App\UI\Firewall\Forms\RemoveResourceForm;
use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\Modals\BaseEditModal;

class RemoveResourceModal extends BaseEditModal implements ClientArea, AdminArea
{
    protected $id               = 'removeResourceModal';
    protected $name             = 'removeResourceModal';
    protected $title            = 'removeResourceModal';

    public function initContent()
    {
        $this->addForm(new RemoveResourceForm());
    }
}