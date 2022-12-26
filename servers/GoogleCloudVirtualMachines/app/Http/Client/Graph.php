<?php


namespace ModulesGarden\Servers\GoogleCloudVirtualMachines\App\Http\Client;

use ModulesGarden\Servers\GoogleCloudVirtualMachines\App\Api\GoogleServiceComputeFactory;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\App\Api\InstanceFactory;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\App\Api\ProjectFactory;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\App\UI\Client\Graph\Pages\CpuGraph;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\App\UI\Client\Graph\Pages\DiskGraph;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\App\UI\Client\Graph\Pages\MemoryGraph;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\App\UI\Client\Graph\Pages\NetworkGraph;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\Helper;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\Http\AbstractClientController;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Traits\WhmcsParams;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\App\Traits\ProductSetting;

class Graph extends AbstractClientController
{
    use WhmcsParams, ProductSetting;

    public function index()
    {
        if ($this->getWhmcsParamByKey('status') != 'Active')
        {
            return;
        }

        if($this->productSetting()->graphs != 'on'){
            return;
        }

        $instace = (new InstanceFactory())->fromParams();
        $this->compute = (new GoogleServiceComputeFactory())->fromParams();
        $poject = (new ProjectFactory())->fromParams();
        $machineType = $this->compute->instances->get($poject, $instace->getZone(), $instace->getId())->machineType;

        $view = Helper\view();
        $view->addElement(CpuGraph::class);

        if(strpos($machineType, 'machineTypes/e2') !== false){
            $view->addElement(MemoryGraph::class);
        }

        $view->addElement(NetworkGraph::class)
            ->addElement(DiskGraph::class);

        return $view;
    }
}