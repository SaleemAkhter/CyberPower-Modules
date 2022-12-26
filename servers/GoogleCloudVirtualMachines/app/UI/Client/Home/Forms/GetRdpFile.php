<?php


namespace ModulesGarden\Servers\GoogleCloudVirtualMachines\App\UI\Client\Home\Forms;


use ModulesGarden\Servers\GoogleCloudVirtualMachines\App\UI\Client\Home\Providers\ServiceActions;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Widget\Forms\BaseForm;

class GetRdpFile extends BaseForm implements AdminArea, ClientArea
{
    protected $id = 'getRdpFileForm';
    protected $name = 'getRdpFileForm';
    protected $title = 'getRdpFileFormTitle';

    protected $allowedActions = ['getRdpFile'];

    public function initContent()
    {
        $this->setFormType('getRdpFile');
        $this->setProvider(new ServiceActions());

        $this->setConfirmMessage('confirmGetRdpFile');
    }
}