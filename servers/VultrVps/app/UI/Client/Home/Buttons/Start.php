<?php

namespace ModulesGarden\Servers\VultrVps\App\UI\Client\Home\Buttons;

use ModulesGarden\Servers\VultrVps\App\UI\Client\Home\Modals\StartInstance;
use ModulesGarden\Servers\VultrVps\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\VultrVps\Core\UI\Interfaces\ClientArea;

class Start extends VpsActionButton implements AdminArea, ClientArea
{
    protected $id = 'start';
    protected $name = 'start';
    protected $title = 'startTitle';

    public function initContent()
    {
        $this->setIconFileName('powerOnButton.png');

        $modal = new StartInstance();
        $this->initLoadModalAction($modal);
    }
}
