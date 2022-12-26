<?php

namespace ModulesGarden\Servers\GoogleCloudVirtualMachines\App\UI\Client\Home\Buttons;

use ModulesGarden\Servers\GoogleCloudVirtualMachines\App\UI\Client\Home\Modals\StopInstance;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Interfaces\ClientArea;

class Stop extends VpsActionButton  implements AdminArea, ClientArea
{
    protected $id = 'stop';
    protected $name = 'stop';
    protected $title = 'stopTitle';

    public function initContent()
    {
        $this->setIconFileName('powerOffButton.png');

        $modal = new StopInstance();
        $this->initLoadModalAction($modal);
    }
}
