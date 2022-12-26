<?php


namespace ModulesGarden\Servers\GoogleCloudVirtualMachines\App\UI\Client\Home\Modals;


use ModulesGarden\Servers\GoogleCloudVirtualMachines\App\UI\Client\Home\Forms\GetRdpFile;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Widget\Modals\ModalConfirmSuccess;

class GetRdpFileInstance extends ModalConfirmSuccess implements AdminArea, ClientArea
{
    protected $id = 'getRdpFileInstance';
    protected $name = 'getRdpFileInstance';
    protected $title = 'getRdpFileInstanceTitle';

    public function initContent()
    {
        $this->addForm(new GetRdpFile());
    }

    protected $htmlAttributes = [
        'href'        => 'javascript:;',
        'data-toggle' => 'lu-tooltip',
        '@click'      => 'downloadRdpFile($event)'
    ];
}