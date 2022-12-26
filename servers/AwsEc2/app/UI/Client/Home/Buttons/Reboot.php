<?php

namespace ModulesGarden\Servers\AwsEc2\App\UI\Client\Home\Buttons;

use ModulesGarden\Servers\AwsEc2\App\UI\Client\Home\Modals\RebootInstance;
use ModulesGarden\Servers\AwsEc2\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\AwsEc2\Core\UI\Interfaces\ClientArea;

class Reboot extends VpsActionButton implements AdminArea, ClientArea
{
    protected $id = 'reboot';
    protected $name = 'reboot';
    protected $title = 'rebootTitle';

    public function initContent()
    {
        $this->setIconFileName('reboot.png');

        $modal = new RebootInstance();
        $this->initLoadModalAction($modal);
    }
}
