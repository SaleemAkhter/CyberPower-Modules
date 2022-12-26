<?php

namespace ModulesGarden\Servers\AwsEc2\App\UI\Client\Home\Buttons;

use ModulesGarden\Servers\AwsEc2\App\UI\Client\Home\Modals\StopInstance;
use ModulesGarden\Servers\AwsEc2\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\AwsEc2\Core\UI\Interfaces\ClientArea;

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
