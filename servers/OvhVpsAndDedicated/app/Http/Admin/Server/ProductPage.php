<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\Http\Admin\Server;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\Models\Product;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Models\Whmcs\Hosting;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Disks\Pages\DisksList;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Ips\Pages\IpsList;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Ips\Pages\IpsListDedicated;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Product\Pages\Dedicated\GraphPage;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Product\Pages\ServerInformation;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Product\Pages\ServerMonitoring;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Snapshots\Pages\SnapshotPage;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Client\Home\Pages\Vps\ControlPanel;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\Helper;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\Http\AbstractController;



/**
 * Description of Clientsservices
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class ProductPage extends AbstractController
{


    public function index()
    {
        $productId = $this->getRequestValue('id');
        $serverGroupId = $this->getRequestValue('servergroup', 0);
        if($serverGroupId )
        {
            \ModulesGarden\OvhVpsAndDedicated\Core\Models\Whmcs\Product::where('id', $productId)->update(['servergroup' => $serverGroupId]);
        }

        $serverType = Product::getServerTypeById($productId);

        if(method_exists($this, $serverType))
        {
            return $this->{$serverType}();
        }

    }

    public function servicePageIndex()
    {
        $productId = Hosting::where('id', $this->getServiceId())->first()->packageid;
        $serverType = Product::getServerTypeById($productId);

        $method = 'adminServicesTabFields' . ucfirst($serverType);
        if(method_exists($this, $method))
        {
            return $this->{$method}();
        }
    }


    public function adminServicesTabFieldsVps()
    {
        return Helper\viewIntegrationAddon()
            ->addElement(ControlPanel::class)
            ->addElement(ServerInformation::class)
            ->addElement(ServerMonitoring::class)
            ->addElement(SnapshotPage::class)
            ->addElement(IpsList::class)
            ->addElement(DisksList::class);
    }

    public function adminServicesTabFieldsDedicated()
    {
        return Helper\viewIntegrationAddon()
            ->addElement(\ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Home\Pages\Dedicated\ControlPanel::class)
            ->addElement(\ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Product\Pages\Dedicated\ServerInformation::class)
            ->addElement(IpsListDedicated::class)
            ->addElement(GraphPage::class);
    }


    public function dedicated()
    {
        return Helper\viewIntegrationAddon()
            ->addElement(\ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Configuration\Pages\CronInformation::class)
            ->addElement(\ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Configuration\Pages\Dedicated\Form::class)
            ->addElement(\ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Configuration\Pages\SMTPForm::class)
            ->addElement(\ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Configuration\Pages\Dedicated\EmailOptions::class)
            ->addElement(\ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Configuration\Pages\Dedicated\Automation::class)
            ->addElement(\ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Configuration\Pages\Dedicated\Features::class)
            ->addElement(\ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Configuration\Pages\Dedicated\ConfigurableOptions::class);
    }

    public function vps()
    {
        return Helper\viewIntegrationAddon()

            ->addElement(\ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Configuration\Pages\CronInformation::class)
            ->addElement(\ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Configuration\Pages\Form::class)
            ->addElement(\ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Configuration\Pages\SMTPForm::class)
            ->addElement(\ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Configuration\Pages\EmailOptions::class)
            ->addElement(\ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Configuration\Pages\Automation::class)
            ->addElement(\ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Configuration\Pages\Features::class)
            ->addElement(\ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Configuration\Pages\ConfigurableOptions::class);
    }

    private function getServiceId()
    {
        $serviceId = $this->getRequestValue('productselect');
        if($serviceId)
        {
            return $serviceId;
        }

        return $this->getRequestValue('id');
    }
}
