<?php

namespace ModulesGarden\Servers\GoogleCloudVirtualMachines\App\UI\Client\Home\Modals;

use ModulesGarden\Servers\GoogleCloudVirtualMachines\App\UI\Client\Home\Forms\Stop;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Widget\Modals\ModalConfirmDanger;


class StopInstance extends ModalConfirmDanger implements AdminArea, ClientArea
{
    protected $id = 'stopInstance';
    protected $name = 'stopInstance';
    protected $title = 'stopInstanceTitle';

    public function initContent()
    {
        $this->addForm(new Stop());
    }
}