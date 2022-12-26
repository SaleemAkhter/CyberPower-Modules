<?php


namespace ModulesGarden\Servers\GoogleCloudVirtualMachines\App\UI\Client\Home\Buttons;


use ModulesGarden\Servers\GoogleCloudVirtualMachines\App\UI\Client\Home\Modals\ResetWindowsPassword;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Interfaces\ClientArea;

class ResetPassword extends VpsActionButton implements ClientArea
{
    protected $id = 'resetPassword';
    protected $name = 'resetPassword';
    protected $title = 'resetPassword';

    public function initContent()
    {
        $this->setIconFileName('password.png');

        $modal = new ResetWindowsPassword();
        $this->initLoadModalAction($modal);
    }
}