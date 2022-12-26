<?php

namespace ModulesGarden\Servers\AwsEc2\App\UI\Client\Home\Buttons;

use ModulesGarden\Servers\AwsEc2\App\UI\Client\Home\Modals\WindowsPasswordDecode;
use ModulesGarden\Servers\AwsEc2\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\AwsEc2\Core\UI\Interfaces\ClientArea;

class WindowsPassword extends VpsActionButton implements AdminArea, ClientArea
{
    protected $id = 'windowsPassword';
    protected $name = 'windowsPassword';
    protected $title = 'windowsPasswordTitle';

    public function initContent()
    {
        $this->setIconFileName('password.png');

        $modal = new WindowsPasswordDecode();
        $this->initLoadModalAction($modal);
    }
}
