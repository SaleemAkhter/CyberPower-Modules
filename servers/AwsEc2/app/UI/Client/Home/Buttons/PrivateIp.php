<?php


namespace ModulesGarden\Servers\AwsEc2\App\UI\Client\Home\Buttons;

use ModulesGarden\Servers\AwsEc2\App\UI\Client\Home\Modals\GetKeyModal;
use ModulesGarden\Servers\AwsEc2\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\AwsEc2\Core\UI\Interfaces\ClientArea;

class PrivateIp extends VpsActionButton implements AdminArea, ClientArea
{
    protected $id = 'privateIp';
    protected $name = 'privateIp';
    protected $title = 'privateIpTitle';

    public function initContent()
    {
        $this->setIconFileName('password.png');

        $modal = new GetKeyModal();
        $this->initLoadModalAction($modal);
    }

}
