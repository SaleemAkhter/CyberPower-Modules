<?php

namespace ModulesGarden\Servers\GoogleCloudVirtualMachines\App\UI\Client\Home\Buttons;

use ModulesGarden\Servers\GoogleCloudVirtualMachines\App\UI\Client\Home\Modals\ResetInstance;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Interfaces\ClientArea;

class Reset extends VpsActionButton implements AdminArea, ClientArea
{
    protected $id = 'reset';
    protected $name = 'reset';
    protected $title = 'reset';

    public function initContent()
    {
        $this->setIconFileName('reboot.png');

        $modal = new ResetInstance();
        $this->initLoadModalAction($modal);
    }
}
