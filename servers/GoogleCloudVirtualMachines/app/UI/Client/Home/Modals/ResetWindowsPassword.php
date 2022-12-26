<?php


namespace ModulesGarden\Servers\GoogleCloudVirtualMachines\App\UI\Client\Home\Modals;


use ModulesGarden\Servers\GoogleCloudVirtualMachines\App\UI\Client\Home\Forms\Reset;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\App\UI\Client\Home\Forms\ResetPassword;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Widget\Modals\BaseEditModal;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Widget\Modals\ModalConfirmDanger;

class ResetWindowsPassword extends BaseEditModal implements ClientArea
{
    protected $id = 'resetWindowsPassword';
    protected $name = 'resetWindowsPassword';
    protected $title = 'resetWindowsPassword';

    public function initContent()
    {
        $this->addClass('resetModal');
        $this->addForm(new ResetPassword());
    }
}