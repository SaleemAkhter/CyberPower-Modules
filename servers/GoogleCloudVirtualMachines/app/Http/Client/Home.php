<?php

namespace ModulesGarden\Servers\GoogleCloudVirtualMachines\App\Http\Client;

use ModulesGarden\Servers\GoogleCloudVirtualMachines\App\Api\GoogleServiceComputeFactory;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\App\Api\InstanceFactory;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\App\Api\ProjectFactory;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\App\UI\Client\Home\Pages\ScheduledTasks;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\App\UI\Client\Home\Pages\StatusWidget;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\Helper;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\Http\AbstractClientController;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\ResponseTemplates\RawDataJsonResponse;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Traits\WhmcsParams;

/**
 * Description of Home
 *
 * @author Sławomir Miśkowicz <slawomir@modulesgarden.com>
 */
class Home extends AbstractClientController
{
    use WhmcsParams;

    public function index()
    {
        if ($this->getWhmcsParamByKey('status') === 'Active')
        {
            return Helper\view()
                ->addElement(StatusWidget::class);
        }
    }

    public function getRdpFile()
    {
        $instance = (new InstanceFactory())->fromParams();
        $compute = (new GoogleServiceComputeFactory())->fromParams();
        $project = (new ProjectFactory())->fromParams();
        $ip = $compute->instances->get($project, $instance->getZone(), $instance->getId())["networkInterfaces"][0]->accessConfigs[0]->natIP;

        $this->createAndDownloadRdpFile($ip);
    }

    private function createAndDownloadRdpFile($ip)
    {
        $fileName = 'RemoteDesktopConnection.rdp';
        $file = 'full address:s:' . $ip . "\n";
        $file .= 'username:s: admin';

        header("Content-Description: File Transfer");
        header("Content-Length: ". strlen($file).";");
        header("Content-Disposition: attachment; filename=$fileName");
        header("Content-Type: application/rdp; ");
        header("Content-Transfer-Encoding: binary");

        print $file;
        exit();
    }
}
