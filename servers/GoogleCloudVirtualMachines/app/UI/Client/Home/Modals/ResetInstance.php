<?php

namespace ModulesGarden\Servers\GoogleCloudVirtualMachines\App\UI\Client\Home\Modals;

use ModulesGarden\Servers\GoogleCloudVirtualMachines\App\UI\Client\Home\Forms\Reset;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Widget\Modals\ModalConfirmDanger;

class ResetInstance extends ModalConfirmDanger implements AdminArea, ClientArea
{
    protected $id = 'resetInstance';
    protected $name = 'resetInstance';
    protected $title = 'resetInstance';

    public function initContent()
    {
        $this->addForm(new Reset());
    }
}
