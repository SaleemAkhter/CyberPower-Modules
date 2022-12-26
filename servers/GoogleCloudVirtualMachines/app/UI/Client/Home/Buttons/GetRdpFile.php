<?php


namespace ModulesGarden\Servers\GoogleCloudVirtualMachines\App\UI\Client\Home\Buttons;


use ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Widget\Buttons\ButtonDownloadRedirect;

class GetRdpFile extends ButtonDownloadRedirect implements ClientArea
{
    protected $id = 'getRdpFile';
    protected $name = 'getRdpFile';
    protected $title = 'getRdpFileTitle';
    protected $class = ['lu-tile', 'lu-tile--btn'];

    public function initContent()
    {
        $this->setIconFileName('downloadRDP.png');
    }

}