<?php


namespace ModulesGarden\Servers\AwsEc2\App\UI\Client\Home\Buttons;


use ModulesGarden\Servers\AwsEc2\App\UI\Client\Home\Modals\InjectSshKeyModal;
use ModulesGarden\Servers\AwsEc2\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\AwsEc2\Core\UI\Interfaces\ClientArea;

class InjectSshKey extends VpsActionButton implements AdminArea, ClientArea
{
    protected $id = 'injectSshKey';
    protected $name = 'injectSshKey';
    protected $title = 'injectSshKeyTitle';

    public function initContent()
    {
        $this->setIconFileName('password.png');

        $modal = new InjectSshKeyModal();
        $this->initLoadModalAction($modal);
    }
}